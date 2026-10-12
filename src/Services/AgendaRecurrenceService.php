<?php

namespace Bale\Cms\Services;

use Bale\Cms\Models\Agenda;
use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use RRule\RRule;
use Throwable;

/**
 * Satu sumber kebenaran untuk ekspansi recurrence agenda.
 *
 * Prinsip:
 * - `start_date` adalah anchor (DTSTART). `rrule` TIDAK memuat DTSTART.
 * - Virtual: occurrence dihitung saat dibaca, bukan disimpan per tanggal.
 * - Durasi tiap occurrence = `end_date - start_date` dari master.
 */
class AgendaRecurrenceService
{
    /**
     * Batas rentang ekspansi agar rule tanpa UNTIL/COUNT tidak loop tak berujung.
     */
    public const MAX_SPAN_DAYS = 366;

    /**
     * Batas jumlah occurrence per pemanggilan.
     */
    public const MAX_OCCURRENCES = 1000;

    /**
     * Bangun RRULE kanonik dari konfigurasi builder.
     *
     * Urutan parameter: FREQ;INTERVAL;BYMONTH;BYMONTHDAY;BYDAY;BYSETPOS;UNTIL/COUNT.
     * Bentuk tunggal untuk "hari ke-n": BYDAY + BYSETPOS (bukan `1MO,3MO`).
     *
     * @param  array{
     *     frequency?: string,
     *     interval?: int,
     *     byday?: array<int, string>,
     *     monthly_mode?: string,
     *     bymonthday?: int,
     *     bymonth?: int,
     *     bysetpos?: array<int, int|string>,
     *     end_mode?: string,
     *     until?: CarbonInterface|string|null,
     *     count?: int,
     * }  $config
     */
    public function build(array $config): string
    {
        $freq = strtoupper((string) ($config['frequency'] ?? 'DAILY'));
        $parts = ['FREQ='.$freq];

        $interval = (int) ($config['interval'] ?? 1);
        if ($interval > 1) {
            $parts[] = 'INTERVAL='.$interval;
        }

        if ($freq === 'YEARLY') {
            if (! empty($config['bymonth'])) {
                $parts[] = 'BYMONTH='.(int) $config['bymonth'];
            }
            if (! empty($config['bymonthday'])) {
                $parts[] = 'BYMONTHDAY='.(int) $config['bymonthday'];
            }
        }

        if ($freq === 'MONTHLY' && ($config['monthly_mode'] ?? null) === 'date') {
            $parts[] = 'BYMONTHDAY='.(int) ($config['bymonthday'] ?? 1);
        }

        $byday = array_values(array_filter(
            (array) ($config['byday'] ?? []),
            fn ($v) => is_string($v) && $v !== '',
        ));

        if ($byday && in_array($freq, ['WEEKLY', 'MONTHLY'], true)) {
            $parts[] = 'BYDAY='.implode(',', $byday);
        }

        if ($freq === 'MONTHLY' && ($config['monthly_mode'] ?? null) === 'nth') {
            $setpos = array_values(array_filter(
                (array) ($config['bysetpos'] ?? []),
                fn ($v) => $v !== null && $v !== '',
            ));

            if ($setpos) {
                $parts[] = 'BYSETPOS='.implode(',', array_map('intval', $setpos));
            }
        }

        $endMode = $config['end_mode'] ?? 'never';
        if ($endMode === 'after' && ! empty($config['count'])) {
            $parts[] = 'COUNT='.(int) $config['count'];
        } elseif ($endMode === 'on' && ! empty($config['until'])) {
            $parts[] = 'UNTIL='.$this->formatUntil($config['until']);
        }

        return implode(';', $parts);
    }

    /**
     * Ringkasan aturan ulang yang bisa dibaca manusia.
     */
    public function describe(?string $rrule, ?CarbonInterface $dtstart = null): string
    {
        if (! is_string($rrule) || trim($rrule) === '') {
            return __('Sekali jalan');
        }

        try {
            $rule = new RRule(
                $this->normalizeRrule($rrule),
                $dtstart ? Carbon::instance($dtstart) : null,
            );

            $text = $rule->humanReadable([
                'locale' => app()->getLocale(),
                'fallback' => 'en',
                'custom_path' => dirname(__DIR__, 2).'/resources/i18n/rrule',
                'date_formatter' => fn ($date) => Carbon::instance($date)
                    ->locale(app()->getLocale())
                    ->translatedFormat('d F Y'),
            ]);

            return is_array($text) ? implode(' ', $text) : (string) $text;
        } catch (Throwable) {
            return $rrule;
        }
    }

    /**
     * Validasi string RRULE. `true` bila dapat di-parse dan konsisten.
     *
     * Menolak: string kosong, memuat DTSTART (harus di-inject dari start_date),
     * RRULE rusak, dan rule yang tidak menghasilkan occurrence (mis. UNTIL
     * sebelum DTSTART).
     */
    public function validate(?string $rrule, ?CarbonInterface $dtstart = null): bool
    {
        if (! is_string($rrule) || trim($rrule) === '') {
            return false;
        }

        if (stripos($rrule, 'DTSTART') !== false) {
            return false;
        }

        try {
            $rule = new RRule(
                $this->normalizeRrule($rrule),
                $dtstart ? Carbon::instance($dtstart) : null,
            );
        } catch (Throwable) {
            return false;
        }

        if ($dtstart && empty($rule->getOccurrences(1))) {
            return false;
        }

        return true;
    }

    /**
     * Ekspansi occurrence dalam rentang [$from, $to].
     *
     * Non-recurring → base event (bila beririsan dengan rentang).
     * Recurring → ekspansi via RRule; DTSTART di-inject dari `start_date`.
     *
     * @return Collection<int, array{start: CarbonInterface, end: CarbonInterface, all_day: bool}>
     */
    public function occurrencesBetween(Agenda $agenda, CarbonInterface $from, CarbonInterface $to): Collection
    {
        $from = Carbon::instance($from);
        $to = Carbon::instance($to);

        // Clamp rentang agar rule tanpa akhir aman.
        if ($from->diffInDays($to) > self::MAX_SPAN_DAYS) {
            $to = $from->copy()->addDays(self::MAX_SPAN_DAYS);
        }

        $dtstart = Carbon::instance($agenda->start_date);
        $duration = $this->durationSeconds($agenda);

        if (! $agenda->is_recurring || ! $agenda->rrule) {
            $end = $duration > 0 ? $dtstart->copy()->addSeconds($duration) : $dtstart->copy();

            if ($end->lt($from) || $dtstart->gt($to)) {
                return collect();
            }

            return collect([$this->makeEvent($dtstart, $duration, (bool) $agenda->all_day)]);
        }

        $rule = new RRule($this->normalizeRrule($agenda->rrule), $dtstart);

        $occurrences = $rule->getOccurrencesBetween($from, $to, self::MAX_OCCURRENCES);

        return collect($occurrences)->map(
            fn ($occurrence) => $this->makeEvent(Carbon::instance($occurrence), $duration, (bool) $agenda->all_day),
        );
    }

    /**
     * Durasi tiap occurrence (detik) = end_date - start_date master.
     */
    private function durationSeconds(Agenda $agenda): int
    {
        if (! $agenda->end_date) {
            return 0;
        }

        $start = Carbon::instance($agenda->start_date);
        $end = Carbon::instance($agenda->end_date);

        return max(0, $end->getTimestamp() - $start->getTimestamp());
    }

    /**
     * @return array{start: CarbonInterface, end: CarbonInterface, all_day: bool}
     */
    private function makeEvent(CarbonInterface $start, int $duration, bool $allDay): array
    {
        return [
            'start' => $start,
            'end' => $start->copy()->addSeconds($duration),
            'all_day' => $allDay,
        ];
    }

    /**
     * Buang DTSTART/RRULE prefix agar hanya body rule yang tersisa.
     */
    private function normalizeRrule(string $rrule): string
    {
        $rrule = preg_replace('/(^|\n)\s*DTSTART[^\n]*/i', '', $rrule) ?? $rrule;
        $rrule = preg_replace('/^\s*RRULE:/i', '', trim($rrule)) ?? $rrule;

        return trim($rrule, "; \t\n\r\0\x0B");
    }

    private function formatUntil(CarbonInterface|string $until): string
    {
        $date = $until instanceof CarbonInterface
            ? Carbon::instance($until)
            : Carbon::parse($until);

        return $date->copy()->endOfDay()->utc()->format('Ymd\THis\Z');
    }
}

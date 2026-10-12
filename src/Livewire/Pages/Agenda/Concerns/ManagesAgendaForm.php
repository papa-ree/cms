<?php

namespace Bale\Cms\Livewire\Pages\Agenda\Concerns;

use Bale\Cms\Services\AgendaRecurrenceService;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;

/**
 * State + perilaku bersama untuk form create/edit agenda.
 *
 * Builder → RRULE memakai satu bentuk kanonik (`BYSETPOS` untuk "hari tertentu
 * minggu ke-n"). Preview live via `describe()`, tombol simpan diblokir saat
 * `validate()` gagal (lihat PRD-AGENDA §6.2).
 */
trait ManagesAgendaForm
{
    public $title;

    public $description;

    public $location;

    public $category;

    public $start_date;

    public $end_date;

    public $timezone;

    public $is_visible = true;

    public $all_day = false;

    public $is_recurring = false;

    public $frequency = 'daily';

    public $interval = 1;

    public $weeklyDays = [];

    public $monthly_mode = 'date';

    public $nthPositions = [];

    public $yearlyMonth;

    public $yearlyDay;

    public $end_mode = 'never';

    public $until;

    public $count = 1;

    public $frequencyOptions = [
        ['label' => 'Harian', 'value' => 'daily'],
        ['label' => 'Mingguan', 'value' => 'weekly'],
        ['label' => 'Bulanan', 'value' => 'monthly'],
        ['label' => 'Tahunan', 'value' => 'yearly'],
    ];

    public $monthlyModeOptions = [
        ['label' => 'Berdasarkan tanggal', 'value' => 'date'],
        ['label' => 'Hari tertentu dalam bulan', 'value' => 'nth'],
    ];

    public $endModeOptions = [
        ['label' => 'Tidak pernah', 'value' => 'never'],
        ['label' => 'Pada tanggal', 'value' => 'on'],
        ['label' => 'Setelah N kali', 'value' => 'after'],
    ];

    public $weekDays = [
        ['label' => 'Sen', 'value' => 'MO'],
        ['label' => 'Sel', 'value' => 'TU'],
        ['label' => 'Rab', 'value' => 'WE'],
        ['label' => 'Kam', 'value' => 'TH'],
        ['label' => 'Jum', 'value' => 'FR'],
        ['label' => 'Sab', 'value' => 'SA'],
        ['label' => 'Min', 'value' => 'SU'],
    ];

    public $nthPositionOptions = [
        ['label' => '1', 'value' => '1'],
        ['label' => '2', 'value' => '2'],
        ['label' => '3', 'value' => '3'],
        ['label' => '4', 'value' => '4'],
        ['label' => '5', 'value' => '5'],
        ['label' => 'Terakhir', 'value' => '-1'],
    ];

    public function rules(): array
    {
        TenantConnectionService::ensureActive();

        return [
            'title' => ['required', 'string', 'min:3', 'max:120'],
            'description' => ['nullable', 'string'],
            'location' => ['nullable', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:120'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'timezone' => ['nullable', 'string', 'max:64'],
            'interval' => ['required', 'integer', 'min:1', 'max:999'],
            'until' => ['nullable', 'date'],
            'count' => ['nullable', 'integer', 'min:1', 'max:999'],
        ];
    }

    protected function messages()
    {
        return [
            'title.required' => __('Judul agenda wajib diisi.'),
            'title.min' => __('Judul agenda minimal 3 karakter.'),
            'title.max' => __('Judul agenda maksimal 120 karakter.'),
            'start_date.required' => __('Tanggal mulai wajib diisi.'),
            'end_date.after_or_equal' => __('Tanggal selesai harus sama dengan atau setelah tanggal mulai.'),
            'interval.required' => __('Interval wajib diisi.'),
            'interval.min' => __('Interval minimal 1.'),
            'count.min' => __('Jumlah kejadian minimal 1.'),
        ];
    }

    public function updatedStartDate($value)
    {
        if (! $value) {
            return;
        }

        try {
            $date = Carbon::parse($value);
        } catch (\Throwable) {
            return;
        }

        $this->yearlyMonth = $date->month;
        $this->yearlyDay = $date->day;

        if (empty($this->weeklyDays)) {
            $this->weeklyDays = [strtoupper(substr($date->englishDayOfWeek, 0, 2))];
        }
    }

    #[Computed]
    public function preview(): string
    {
        return app(AgendaRecurrenceService::class)->describe(
            $this->buildRrule(),
            $this->startDateCarbon(),
        );
    }

    #[Computed]
    public function rruleValid(): bool
    {
        if (! $this->is_recurring) {
            return true;
        }

        if ($this->frequency === 'weekly' && empty($this->weeklyDays)) {
            return false;
        }

        if ($this->frequency === 'monthly'
            && $this->monthly_mode === 'nth'
            && (empty($this->weeklyDays) || empty($this->nthPositions))) {
            return false;
        }

        $rrule = $this->buildRrule();

        return $rrule !== null
            && app(AgendaRecurrenceService::class)->validate($rrule, $this->startDateCarbon());
    }

    /**
     * RRULE kanonik dari state builder (null saat non-recurring).
     */
    public function buildRrule(): ?string
    {
        if (! $this->is_recurring) {
            return null;
        }

        return app(AgendaRecurrenceService::class)->build($this->makeConfig());
    }

    private function makeConfig(): array
    {
        $config = [
            'frequency' => $this->frequency,
            'interval' => max(1, (int) $this->interval),
            'end_mode' => $this->end_mode,
        ];

        if ($this->frequency === 'weekly' && $this->weeklyDays) {
            $config['byday'] = $this->orderedDays($this->weeklyDays);
        }

        if ($this->frequency === 'monthly') {
            if ($this->monthly_mode === 'nth') {
                $config['monthly_mode'] = 'nth';
                $config['byday'] = $this->orderedDays($this->weeklyDays ?? []);
                $config['bysetpos'] = $this->orderedPositions($this->nthPositions ?? []);
            } else {
                $config['monthly_mode'] = 'date';
                $config['bymonthday'] = $this->startDateCarbon()->day;
            }
        }

        if ($this->frequency === 'yearly') {
            $config['bymonth'] = (int) $this->yearlyMonth;
            $config['bymonthday'] = (int) $this->yearlyDay;
        }

        if ($this->end_mode === 'on' && $this->until) {
            $config['until'] = $this->until;
        } elseif ($this->end_mode === 'after' && $this->count) {
            $config['count'] = (int) $this->count;
        }

        return $config;
    }

    /**
     * Urutkan hari ke urutan kanonik (Senin→Minggu) agar RRULE deterministik
     * apa pun urutan klik pengguna.
     */
    private function orderedDays(array $days): array
    {
        $order = ['MO', 'TU', 'WE', 'TH', 'FR', 'SA', 'SU'];

        $days = array_values(array_unique($days));

        usort($days, fn ($a, $b) => array_search($a, $order) <=> array_search($b, $order));

        return $days;
    }

    /**
     * Urutkan posisi ke-n menaik, dengan "Last" (-1) selalu di akhir.
     */
    private function orderedPositions(array $positions): array
    {
        $positions = array_values(array_unique($positions));

        $weight = fn ($p) => (int) $p === -1 ? PHP_INT_MAX : (int) $p;

        usort($positions, fn ($a, $b) => $weight($a) <=> $weight($b));

        return $positions;
    }

    private function startDateCarbon(): Carbon
    {
        try {
            return $this->start_date ? Carbon::parse($this->start_date) : now();
        } catch (\Throwable) {
            return now();
        }
    }

    private function normalizeInputs(): void
    {
        $this->interval = ($this->interval === '' || $this->interval === null)
            ? 1
            : (int) $this->interval;

        $this->count = ($this->count === '' || $this->count === null)
            ? null
            : (int) $this->count;

        $this->until = $this->until === '' ? null : $this->until;
        $this->end_date = $this->end_date === '' ? null : $this->end_date;
    }
}

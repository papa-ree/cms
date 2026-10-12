<?php

namespace Bale\Cms\Livewire\Pages\Agenda;

use Bale\Cms\Livewire\Pages\Agenda\Concerns\ManagesAgendaForm;
use Bale\Cms\Models\Agenda;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Ubah Agenda')]
class EditAgenda extends Component
{
    use ManagesAgendaForm;

    #[Locked]
    public $id;

    public function mount($agenda)
    {
        $this->authorize('bale-agenda.read');

        TenantConnectionService::ensureActive();

        $agenda = Agenda::find($agenda);

        abort_unless($agenda, 404);

        $this->id = $agenda->id;
        $this->title = $agenda->title ?? '';
        $this->description = $agenda->description ?? '';
        $this->location = $agenda->location ?? '';
        $this->category = $agenda->category ?? '';
        $this->start_date = $agenda->start_date?->format('Y-m-d\TH:i');
        $this->end_date = $agenda->end_date?->format('Y-m-d\TH:i');
        $this->is_visible = (bool) $agenda->is_visible;
        $this->all_day = (bool) $agenda->all_day;
        $this->timezone = $agenda->timezone ?: config('app.timezone');
        $this->is_recurring = (bool) $agenda->is_recurring;

        $this->syncBuilderFromRrule($agenda->rrule, Carbon::instance($agenda->start_date));
    }

    public function render()
    {
        return view('cms::livewire.pages.agenda.edit-agenda');
    }

    public function update()
    {
        $this->authorize('bale-agenda.update');

        $this->normalizeInputs();
        $this->validate();

        $rrule = $this->buildRrule();

        if ($this->is_recurring && !$rrule) {
            $this->dispatch('toast', message: __('Aturan pengulangan belum lengkap.'), type: 'error');

            return;
        }

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            $agenda = Agenda::on($connection)->findOrFail($this->id);

            $agenda->update([
                'title' => $this->title,
                'description' => $this->description ?: null,
                'location' => $this->location ?: null,
                'category' => $this->category ?: null,
                'start_date' => Carbon::parse($this->start_date),
                'end_date' => $this->end_date ? Carbon::parse($this->end_date) : null,
                'is_visible' => (bool) $this->is_visible,
                'all_day' => (bool) $this->all_day,
                'timezone' => $this->timezone ?: config('app.timezone'),
                'is_recurring' => (bool) $this->is_recurring,
                'rrule' => $rrule,
            ]);

            $this->dispatch('disabling-button', params: false);
            $this->dispatch('toast', message: __('Agenda berhasil diperbarui!'), type: 'success');
            $this->redirectRoute('bale.cms.agendas.index', navigate: true);
        } catch (\Throwable $th) {
            $this->dispatch('disabling-button', params: false);
            info('Agenda update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: __('Terjadi kesalahan!'), type: 'error');
        }
    }

    /**
     * Quick toggle visibility dari halaman edit.
     */
    public function toggleVisible()
    {
        $this->authorize('bale-agenda.update');

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $agenda = Agenda::on($connection)->find($this->id);

            if ($agenda) {
                $agenda->update(['is_visible' => (bool) $this->is_visible]);

                $this->dispatch('toast', message: $this->is_visible
                    ? __('Agenda sekarang terlihat.')
                    : __('Agenda sekarang tersembunyi.'), type: 'success');
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', message: __('Terjadi kesalahan!'), type: 'error');
        }
    }

    /**
     * Isi state builder dari RRULE tersimpan (format kanonik tanpa DTSTART).
     */
    private function syncBuilderFromRrule(?string $rrule, Carbon $start): void
    {
        $this->frequency = 'daily';
        $this->interval = 1;
        $this->weeklyDays = [];
        $this->monthly_mode = 'date';
        $this->nthPositions = [];
        $this->yearlyMonth = $start->month;
        $this->yearlyDay = $start->day;
        $this->end_mode = 'never';
        $this->until = null;
        $this->count = 1;

        if (!$rrule) {
            return;
        }

        $parts = $this->parseRrule($rrule);

        $this->frequency = strtolower($parts['FREQ'] ?? 'daily');
        $this->interval = (int) ($parts['INTERVAL'] ?? 1);
        $this->weeklyDays = $this->splitList($parts['BYDAY'] ?? '');

        if ($this->frequency === 'monthly') {
            $this->monthly_mode = array_key_exists('BYSETPOS', $parts) ? 'nth' : 'date';
            $this->nthPositions = $this->splitList($parts['BYSETPOS'] ?? '');
        }

        if ($this->frequency === 'yearly') {
            $this->yearlyMonth = (int) ($parts['BYMONTH'] ?? $start->month);
            $this->yearlyDay = (int) ($parts['BYMONTHDAY'] ?? $start->day);
        }

        if (array_key_exists('UNTIL', $parts)) {
            $this->end_mode = 'on';
            $this->until = $this->parseUntil($parts['UNTIL'])
                ->setTimezone($this->timezone ?: config('app.timezone'))
                ->format('Y-m-d');
        } elseif (array_key_exists('COUNT', $parts)) {
            $this->end_mode = 'after';
            $this->count = (int) $parts['COUNT'];
        }
    }

    /**
     * @return array<string, string>
     */
    private function parseRrule(string $rrule): array
    {
        $parts = [];

        foreach (explode(';', $rrule) as $segment) {
            $pair = explode('=', $segment, 2);

            if (count($pair) === 2 && trim($pair[0]) !== '') {
                $parts[strtoupper(trim($pair[0]))] = trim($pair[1]);
            }
        }

        return $parts;
    }

    /**
     * @return array<int, string>
     */
    private function splitList(string $value): array
    {
        return array_values(array_filter(
            array_map('trim', explode(',', $value)),
            fn($v) => $v !== '',
        ));
    }

    private function parseUntil(string $value): Carbon
    {
        try {
            return Carbon::createFromFormat('Ymd\THis\Z', $value, 'UTC');
        } catch (\Throwable) {
            return Carbon::parse($value);
        }
    }
}

<?php

namespace Bale\Cms\Livewire\Pages\Agenda;

use Bale\Cms\Livewire\Pages\Agenda\Concerns\ManagesAgendaForm;
use Bale\Cms\Models\Agenda;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Buat Agenda')]
class CreateNewAgenda extends Component
{
    use ManagesAgendaForm;

    public function mount()
    {
        $this->timezone = config('app.timezone');
        $this->yearlyMonth = now()->month;
        $this->yearlyDay = now()->day;
    }

    public function render()
    {
        return view('cms::livewire.pages.agenda.create-new-agenda');
    }

    public function store($data = [])
    {
        $this->authorize('bale-agenda.create');

        $this->title = $data['title'] ?? $this->title;
        $this->description = $data['description'] ?? $this->description;
        $this->location = $data['location'] ?? $this->location;
        $this->category = $data['category'] ?? $this->category;
        $this->start_date = $data['start_date'] ?? $this->start_date;
        $this->end_date = $data['end_date'] ?? $this->end_date;
        $this->interval = $data['interval'] ?? $this->interval;
        $this->until = $data['until'] ?? $this->until;
        $this->count = $data['count'] ?? $this->count;

        $this->normalizeInputs();
        $this->validate();

        $rrule = $this->buildRrule();

        if ($this->is_recurring && !$rrule) {
            $this->dispatch('toast', message: __('Aturan pengulangan belum lengkap.'), type: 'error');

            return;
        }

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            $agenda = (new Agenda)
                ->setConnection($connection)
                ->create([
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

            DB::commit();

            $this->redirectRoute('bale.cms.agendas.index', navigate: true);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Agenda creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: __('Terjadi kesalahan!'), type: 'error');
        }
    }
}

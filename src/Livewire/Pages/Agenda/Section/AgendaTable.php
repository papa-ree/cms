<?php

namespace Bale\Cms\Livewire\Pages\Agenda\Section;

use Bale\Cms\Models\Agenda;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;

#[Layout('cms::layouts.app')]
class AgendaTable extends Component
{
    use HasSafeDelete;

    protected string $modelClass = Agenda::class;

    /**
     * Event hapus khusus (bukan `deleteItem`): item-actions mendispatch
     * `deleteAgenda`, sehingga listener generic `data-table` tidak ikut
     * jalan dan penghapusan tetap tenant-aware (lihat PLAN-AGENDA §A3.1).
     */
    #[On('deleteAgenda')]
    public function deleteAgenda($id)
    {
        $this->deleteId = $id;

        $this->performDelete(__('Agenda berhasil dihapus!'));

        // Anak `data-table` tidak ikut dirender ulang saat induk berubah,
        // jadi minta ia me-refresh daftarnya (lihat TokenTable/BatchTable).
        $this->dispatch('refresh');
    }

    public function render()
    {
        return view('cms::livewire.pages.agenda.section.agenda-table');
    }
}

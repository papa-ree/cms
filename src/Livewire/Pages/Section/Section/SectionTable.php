<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout};
use Livewire\WithPagination;

#[Layout('cms::layouts.app')]
class SectionTable extends Component
{
    use HasSafeDelete, WithPagination;
    protected string $modelClass = Section::class;

    public $query = '';

    public function render()
    {
        return view('cms::livewire.pages.section.section.section-table');
    }

    public function updating($key): void
    {
        if ($key === 'query') {
            $this->resetPage();
        }
    }

    public function updatedPage()
    {
        $this->dispatch('paginated');
    }

    #[Computed]
    public function availableSections()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        return (new Section)->setConnection($connection)->orderBy('name')->where('name', 'like', "%{$this->query}%")->paginate(100);
    }
}
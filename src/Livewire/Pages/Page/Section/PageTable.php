<?php

namespace Bale\Cms\Livewire\Pages\Page\Section;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Component;
use Livewire\Attributes\{Layout, On, Computed};
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Bale\Cms\Models\Page;

#[Layout('cms::layouts.app')]
class PageTable extends Component
{
    use WithPagination, WithoutUrlPagination, HasSafeDelete;
    protected string $modelClass = Page::class;

    public $query = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $filterType = '';

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilter($field)
    {
        if ($field === 'Type')
            $this->reset('filterType');
    }
    public function resetAllFilters()
    {
        $this->reset(['filterType', 'query']);
    }

    #[On('refresh-page')]
    public function render()
    {
        return view('cms::livewire.pages.page.section.page-table');
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
    public function availablePages()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();
        return (new Page)
            ->setConnection($connection)
            ->where('title', 'like', '%' . $this->query . '%')
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
}
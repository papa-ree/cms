<?php

namespace Bale\Cms\Livewire\Pages\Page\Section;

use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, On};
use Bale\Cms\Models\Page;

#[Layout('cms::layouts.app')]
class PageTable extends Component
{
    public $query = '';

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
        return Page::where('title', 'like', '%' . $this->query . '%')->orderBy('title')->paginate(10);
    }
}
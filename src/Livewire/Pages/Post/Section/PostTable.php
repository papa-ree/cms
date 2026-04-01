<?php

namespace Bale\Cms\Livewire\Pages\Post\Section;

use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * Shell component — rendering delegated to DataTable SFC.
 * Query, sort, search, pagination semua dikelola oleh
 * <livewire:core-shared-components::data-table> di dalam view.
 */
#[Layout('cms::layouts.app')]
class PostTable extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.post.section.post-table');
    }
}

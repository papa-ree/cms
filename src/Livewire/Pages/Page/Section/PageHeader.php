<?php

namespace Bale\Cms\Livewire\Pages\Page\Section;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('cms::layouts.app')]
class PageHeader extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.page.section.page-header');
    }
}

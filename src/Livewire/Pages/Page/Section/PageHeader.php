<?php

namespace Bale\Cms\Livewire\Pages\Page\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class PageHeader extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.page.section.page-header');
    }
}
<?php

namespace Bale\Cms\Livewire\Pages\Navigation\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class NavigationSortable extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.navigation.section.navigation-sortable');
    }
}
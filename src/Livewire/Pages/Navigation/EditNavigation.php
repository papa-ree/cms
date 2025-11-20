<?php

namespace Bale\Cms\Livewire\Pages\Navigation;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class EditNavigation extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.navigation.edit-navigation');
    }
}
<?php

namespace Bale\Cms\Livewire\Pages\Navigation;

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Navigation')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.navigation.index');
    }

}
<?php

namespace Bale\Cms\Livewire\SharedComponents;

// use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('cms::layouts.app')]
class PrabanTopbar extends Component
{
    public function render()
    {
        return view('cms::livewire.shared-components.praban-topbar');
    }
}
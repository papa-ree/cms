<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\Layout;

class PrabanTopbar extends Component
{
    public function mount()
    {
        if (session()->has('saved')) {
            LivewireAlert::title(session('saved.title'))->toast()->position('top-end')->success()->show();
        }

        if (session()->has('error')) {
            LivewireAlert::title(session('error.title'))->toast()->position('top-end')->error()->show();
        }
    }
    #[Layout('cms::layouts.app')]
    public function render()
    {
        return view('cms::livewire.shared-components.praban-topbar');
    }
}
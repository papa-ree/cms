<?php

namespace Bale\Cms\Livewire\Pages\Agenda;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Agenda')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.agenda.index');
    }
}

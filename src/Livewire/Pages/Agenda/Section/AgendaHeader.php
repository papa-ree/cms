<?php

namespace Bale\Cms\Livewire\Pages\Agenda\Section;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('cms::layouts.app')]
class AgendaHeader extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.agenda.section.agenda-header');
    }
}

<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Section Management')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.section.index');
    }
}

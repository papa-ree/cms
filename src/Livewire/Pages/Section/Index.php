<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Section Management')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.section.index');
    }
}
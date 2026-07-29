<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Page')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.page.index');
    }
}

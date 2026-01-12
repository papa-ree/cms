<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Page')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.page.index');
    }
}
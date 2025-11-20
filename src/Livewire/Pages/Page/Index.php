<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.page.index');
    }
}
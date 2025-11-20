<?php

namespace Bale\Cms\Livewire\Pages\Overview;

use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('cms::layouts.app')]
    public function render()
    {
        return view('cms::livewire.pages.overview.index');
    }
}
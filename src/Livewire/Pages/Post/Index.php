<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Posts')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.post.index');
    }
}

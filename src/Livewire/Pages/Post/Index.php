<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\{Layout, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Posts')]
class Index extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.post.index');
    }
}
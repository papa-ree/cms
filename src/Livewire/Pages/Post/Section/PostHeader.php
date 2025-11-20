<?php

namespace Bale\Cms\Livewire\Pages\Post\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class PostHeader extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.post.section.post-header');
    }
}
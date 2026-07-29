<?php

namespace Bale\Cms\Livewire\Pages\Post\Section;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('cms::layouts.app')]
class PostHeader extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.post.section.post-header');
    }
}

<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class CmsPostEditorTopbar extends Component
{
    public function render()
    {
        return view('cms::livewire.shared-components.cms-post-editor-topbar');
    }
}

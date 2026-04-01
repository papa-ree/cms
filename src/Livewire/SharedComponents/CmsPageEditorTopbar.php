<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Livewire\Component;
use Livewire\Attributes\{Layout, On};

#[Layout('cms::layouts.app')]
class CmsPageEditorTopbar extends Component
{
    public $saveStatus = 'editing';

    #[On('status-updated')]
    public function updateStatus($status)
    {
        $this->saveStatus = $status;
    }

    public function render()
    {
        return view('cms::livewire.shared-components.cms-page-editor-topbar');
    }
}

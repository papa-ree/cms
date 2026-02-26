<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Livewire\Component;
use Livewire\Attributes\{Computed, Layout};
use Bale\Cms\Models\BaleList;

#[Layout('cms::layouts.app')]
class CmsTopbar extends Component
{
    public function render()
    {
        return view('cms::livewire.shared-components.cms-topbar');
    }

    #[Computed]
    public function activeBale()
    {
        $uuid = session('bale_active_uuid');

        if (!$uuid) {
            return null;
        }

        return BaleList::with('organization')->find($uuid);
    }
}
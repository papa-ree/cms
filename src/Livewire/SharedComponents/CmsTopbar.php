<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Bale\Cms\Models\BaleList;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

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

        if (! $uuid) {
            return null;
        }

        return BaleList::with('organization')->find($uuid);
    }
}

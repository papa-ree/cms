<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Livewire\Component;
use Livewire\Attributes\{Computed, Layout};
use Bale\Cms\Models\BaleList;

class CmsSidebar extends Component
{
    #[Layout('cms::layouts.app')]
    public function render()
    {
        return view('cms::livewire.shared-components.cms-sidebar');
    }

    #[Computed]
    public function availableMenus()
    {
        $menu = [
            ['label' => 'overview', 'url' => 'overview', 'icon' => 'layout-dashboard'],
            ['label' => 'posts', 'url' => 'posts', 'icon' => 'file-text'],
            ['label' => 'pages', 'url' => 'pages', 'icon' => 'file'],
            ['label' => 'navigations', 'url' => 'navigations', 'icon' => 'navigation'],
            ['label' => 'sections', 'url' => 'sections', 'icon' => 'layers'],
        ];

        return $menu;
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
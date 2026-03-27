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
        $allMenus = [
            ['label' => 'posts', 'url' => 'posts', 'icon' => 'file-text', 'permission' => 'bale-post.read'],
            ['label' => 'categories', 'url' => 'categories', 'icon' => 'tag', 'permission' => 'bale-category.read'],
            ['label' => 'pages', 'url' => 'pages', 'icon' => 'file', 'permission' => 'bale-page.read'],
            ['label' => 'navigations', 'url' => 'navigations', 'icon' => 'navigation', 'permission' => 'bale-navigation.read'],
            ['label' => 'sections', 'url' => 'sections', 'icon' => 'layers', 'permission' => 'bale-section.read'],
            ['label' => 'roles', 'url' => 'roles', 'icon' => 'shield-check', 'permission' => 'bale-role.read'],
            ['label' => 'permissions', 'url' => 'permissions', 'icon' => 'shield', 'permission' => 'bale-role.read'],
            ['label' => 'users', 'url' => 'users', 'icon' => 'users', 'permission' => 'bale-user.read'],
        ];

        return array_filter($allMenus, function ($item) {
            return auth()->user()->can($item['permission']);
        });
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

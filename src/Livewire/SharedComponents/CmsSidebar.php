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
    public function cmsMenus(): array
    {
        $menus = [
            ['label' => 'posts',       'url' => 'posts',       'icon' => 'file-text',    'permission' => 'bale-post.read',       'group' => 'cms'],
            ['label' => 'categories',  'url' => 'categories',  'icon' => 'tag',           'permission' => 'bale-category.read',   'group' => 'cms'],
            ['label' => 'pages',       'url' => 'pages',       'icon' => 'file',          'permission' => 'bale-page.read',       'group' => 'cms'],
            ['label' => 'navigations', 'url' => 'navigations', 'icon' => 'navigation',    'permission' => 'bale-navigation.read', 'group' => 'cms'],
            ['label' => 'sections',    'url' => 'sections',    'icon' => 'layers',        'permission' => 'bale-section.read',    'group' => 'cms'],
            ['label' => 'roles',       'url' => 'roles',       'icon' => 'shield-check',  'permission' => 'bale-role.read',       'group' => 'cms'],
            ['label' => 'permissions', 'url' => 'permissions', 'icon' => 'shield',        'permission' => 'bale-role.read',       'group' => 'cms'],
            ['label' => 'users',       'url' => 'users',       'icon' => 'users',         'permission' => 'bale-user.read',       'group' => 'cms'],
        ];

        return array_values(array_filter($menus, fn($item) => auth()->user()->can($item['permission'])));
    }

    #[Computed]
    public function ikmMenus(): array
    {
        $ikmMenuPath = base_path('packages/ikm/src/menu.php');

        if (! file_exists($ikmMenuPath)) {
            return [];
        }

        $menus = include $ikmMenuPath;

        return array_values(array_filter($menus, fn($item) => auth()->user()->can($item['permission'])));
    }

    #[Computed]
    public function lokerMenus(): array
    {
        $lokerMenuPath = base_path('packages/loker/src/menu.php');

        if (! file_exists($lokerMenuPath)) {
            return [];
        }

        $menus = include $lokerMenuPath;

        return array_values(array_filter($menus, fn($item) => auth()->user()->can($item['permission'])));
    }

    /**
     * Tetap dipertahankan agar tidak breaking change jika ada view lain yang menggunakannya.
     * @deprecated Gunakan cmsMenus(), ikmMenus(), atau lokerMenus() secara langsung.
     */
    #[Computed]
    public function availableMenus(): array
    {
        return array_merge($this->cmsMenus, $this->ikmMenus, $this->lokerMenus);
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

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
    public function packageMenus(): array
    {
        $allMenus = [];
        $providers = app()->getLoadedProviders();

        foreach (array_keys($providers) as $provider) {
            // Filter hanya package kita (Bale, Nawasara, Paparee)
            if (
                str_starts_with($provider, 'Bale\\') || 
                str_starts_with($provider, 'Nawasara\\') || 
                str_starts_with($provider, 'Paparee\\')
            ) {
                // Skip CMS provider karena menu-nya sudah di handle di cmsMenus()
                if ($provider === \Bale\Cms\CmsServiceProvider::class) {
                    continue;
                }

                $menus = $this->getPackageMenu($provider);
                if (! empty($menus)) {
                    $allMenus = array_merge($allMenus, $menus);
                }
            }
        }

        return $allMenus;
    }

    /**
     * Mengambil menu dari direktori src package secara dinamis (dev & prod friendly)
     */
    private function getPackageMenu(string $serviceProviderClass): array
    {
        if (! class_exists($serviceProviderClass)) {
            return [];
        }

        try {
            $reflection = new \ReflectionClass($serviceProviderClass);
            $menuPath = dirname($reflection->getFileName()) . '/menu.php';

            if (! file_exists($menuPath)) {
                return [];
            }

            $menus = include $menuPath;

            return array_values(array_filter($menus, fn($item) => auth()->user()->can($item['permission'])));
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Tetap dipertahankan agar tidak breaking change jika ada view lain yang menggunakannya.
     * @deprecated Gunakan cmsMenus() atau packageMenus() secara langsung.
     */
    #[Computed]
    public function availableMenus(): array
    {
        return array_merge($this->cmsMenus, $this->packageMenus);
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

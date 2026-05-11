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
            ['label' => 'posts',       'url' => 'posts',       'icon' => 'file-text',    'permission' => 'bale-post.read',       'group' => 'cms', 'table' => 'posts'],
            ['label' => 'categories',  'url' => 'categories',  'icon' => 'tag',           'permission' => 'bale-category.read',   'group' => 'cms', 'table' => 'categories'],
            ['label' => 'pages',       'url' => 'pages',       'icon' => 'file',          'permission' => 'bale-page.read',       'group' => 'cms', 'table' => 'pages'],
            ['label' => 'navigations', 'url' => 'navigations', 'icon' => 'navigation',    'permission' => 'bale-navigation.read', 'group' => 'cms', 'table' => 'navigations'],
            ['label' => 'sections',    'url' => 'sections',    'icon' => 'layers',        'permission' => 'bale-section.read',    'group' => 'cms', 'table' => 'sections'],
            ['label' => 'roles',       'url' => 'roles',       'icon' => 'shield-check',  'permission' => 'bale-role.read',       'group' => 'cms', 'table' => 'roles'],
            ['label' => 'permissions', 'url' => 'permissions', 'icon' => 'shield',        'permission' => 'bale-role.read',       'group' => 'cms', 'table' => 'permissions'],
            ['label' => 'users',       'url' => 'users',       'icon' => 'users',         'permission' => 'bale-user.read',       'group' => 'cms', 'table' => 'users'],
        ];

        return $this->filterMenus($menus);
    }

    /**
     * Memfilter menu berdasarkan permission dan keberadaan tabel (jika didefinisikan).
     */
    private function filterMenus(array $menus): array
    {
        return array_values(array_filter($menus, function($item) {
            // Cek permission
            if (! auth()->user()->can($item['permission'])) {
                return false;
            }

            // Cek apakah tabel ada (jika didefinisikan)
            if (isset($item['table']) && ! \Illuminate\Support\Facades\Schema::hasTable($item['table'])) {
                return false;
            }

            return true;
        }));
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

            return $this->filterMenus($menus);
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

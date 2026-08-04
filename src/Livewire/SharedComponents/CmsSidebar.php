<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Bale\Cms\Models\BaleList;
use Bale\Cms\Services\TenantManager;
use Bale\Core\Services\MenuRegistry;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\Computed;
use Livewire\Component;

class CmsSidebar extends Component
{
    public function render()
    {
        return view('cms::livewire.shared-components.cms-sidebar');
    }

    /**
     * Menu statis milik CMS (posts, categories, pages, etc.).
     * Difilter berdasarkan permission dan keberadaan tabel di tenant DB.
     */
    #[Computed]
    public function cmsMenus(): array
    {
        $menus = [
            ['label' => 'posts',       'url' => 'posts',       'icon' => 'file-text',   'permission' => 'bale-post.read',       'table' => 'posts'],
            ['label' => 'categories',  'url' => 'categories',  'icon' => 'tag',          'permission' => 'bale-category.read',   'table' => 'categories'],
            ['label' => 'pages',       'url' => 'pages',       'icon' => 'file',         'permission' => 'bale-page.read',       'table' => 'pages'],
            ['label' => 'navigations', 'url' => 'navigations', 'icon' => 'navigation',   'permission' => 'bale-navigation.read', 'table' => 'navigations'],
            ['label' => 'sections',    'url' => 'sections',    'icon' => 'layers',       'permission' => 'bale-section.read',    'table' => 'sections'],
            ['label' => 'roles',       'url' => 'roles',       'icon' => 'shield-check', 'permission' => 'bale-role.read',       'table' => 'roles'],
            ['label' => 'permissions', 'url' => 'permissions', 'icon' => 'shield',       'permission' => 'bale-role.read',       'table' => 'permissions'],
            ['label' => 'users',       'url' => 'users',       'icon' => 'users',        'permission' => 'bale-user.read',       'table' => 'users'],
        ];

        return $this->filterMenus($menus);
    }

    /**
     * Menu dari package eksternal (ikm, loker, dll.) bertipe 'tenant',
     * dibaca dari MenuRegistry yang sudah di-populate saat boot.
     *
     * Item dari MenuRegistry sudah difilter permission/class oleh Registry.
     * Di sini kita tambahkan filter 'table' (cek tenant DB) via filterMenus().
     *
     * Format return: flat array of items (tiap item punya key 'group' untuk grouping di view).
     */
    #[Computed]
    public function packageMenus(): array
    {
        $groups = app(MenuRegistry::class)->getTenantGroups();

        $allItems = [];
        foreach ($groups as $group) {
            foreach ($group['items'] ?? [] as $item) {
                $item['group'] = $item['group'] ?? $group['key'];
                $allItems[] = $item;
            }
        }

        // Filter 'table' menggunakan koneksi tenant aktif
        return $this->filterMenus($allItems);
    }

    /**
     * Memfilter menu berdasarkan permission dan keberadaan tabel di tenant DB.
     * Aman dipanggil bahkan jika koneksi tenant belum aktif — item dengan 'table'
     * akan diperiksa ke koneksi aktif, atau ke default jika belum ada.
     */
    private function filterMenus(array $menus): array
    {
        $connection = TenantManager::getActiveConnection();

        return array_values(array_filter($menus, function ($item) use ($connection) {
            // Cek permission jika didefinisikan dan tidak null
            if (isset($item['permission']) && $item['permission'] !== null) {
                if (! auth()->check() || ! auth()->user()->can($item['permission'])) {
                    return false;
                }
            }

            // Cek apakah tabel ada (jika didefinisikan)
            if (isset($item['table'])) {
                $hasTable = $connection
                    ? Schema::connection($connection)->hasTable($item['table'])
                    : Schema::hasTable($item['table']);

                if (! $hasTable) {
                    return false;
                }
            }

            return true;
        }));
    }

    /**
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

<?php

namespace Bale\Cms\Services;

use Illuminate\Support\Facades\Auth;

/**
 * CmsMenuRegistry — Duplikasi terpisah untuk tenant (cms/loker/ikm)
 * agar optimasi core/rakaca tidak mempengaruhi cms.
 *
 * - Singleton terpisah dari Bale\Core\Services\MenuRegistry
 * - Hanya untuk type=tenant
 * - Tidak ada cache global dengan core — 0% risiko silang
 * - Menu dihitung tiap request (tanpa cache store) agar perubahan
 *   role/permission langsung terlihat
 */
class CmsMenuRegistry
{
    /** @var array<int, array> */
    protected array $groups = [];

    /**
     * Cache permission per user untuk request ini. Bukan `static` agar
     * tidak terbawa ke user lain pada proses panjang (Octane/queue).
     *
     * @var array<string, array<int, string>>
     */
    protected array $permsCache = [];

    public function registerFromProvider(string $providerClass): void
    {
        if (! class_exists($providerClass)) {
            return;
        }
        try {
            $reflection = new \ReflectionClass($providerClass);
            $menuPath = dirname($reflection->getFileName()).'/menu.php';
            if (! file_exists($menuPath)) {
                return;
            }
            $config = include $menuPath;
            if (! is_array($config) || ! isset($config['type'], $config['groups'])) {
                return;
            }
            // Hanya tenant
            if (($config['type'] ?? '') !== 'tenant') {
                return;
            }
            foreach ($config['groups'] as $group) {
                $this->groups[] = array_merge($group, ['_type' => $config['type']]);
            }
        } catch (\Throwable $e) {
        }
    }

    public function getTenantGroups(): array
    {
        return $this->resolveGroups('tenant');
    }

    protected function resolveGroups(string $type): array
    {
        // Tanpa cache store: menu dihitung tiap request agar perubahan
        // role/permission langsung terlihat (tidak perlu optimize:clear).
        $result = [];
        foreach ($this->groups as $group) {
            if (($group['_type'] ?? '') !== $type) {
                continue;
            }
            $filteredItems = $this->filterItems($group['items'] ?? []);
            if (empty($filteredItems)) {
                continue;
            }
            $result[] = [
                'key' => $group['key'] ?? 'unknown',
                'label' => $group['label'] ?? 'Menu',
                'icon' => $group['icon'] ?? 'box',
                'items' => $filteredItems,
            ];
        }

        return $result;
    }

    protected function filterItems(array $items): array
    {
        $userId = Auth::id() ?? 'guest';
        if (! isset($this->permsCache[$userId])) {
            $user = Auth::user();
            if ($user) {
                $user->loadMissing('roles', 'permissions');
                $this->permsCache[$userId] = $user->getAllPermissions()->pluck('name')->toArray();
            } else {
                $this->permsCache[$userId] = [];
            }
        }
        $perms = $this->permsCache[$userId];

        return array_values(array_filter($items, function (array $item) use ($perms): bool {
            if (isset($item['class']) && ! class_exists($item['class'])) {
                return false;
            }
            if (isset($item['permission']) && $item['permission'] !== null) {
                if (! in_array($item['permission'], $perms, true)) {
                    return false;
                }
            }

            return true;
        }));
    }

    public function flush(): void
    {
        $this->groups = [];
        $this->permsCache = [];
    }
}

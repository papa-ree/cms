<?php

namespace Bale\Cms\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

/**
 * CmsMenuRegistry — Duplikasi terpisah untuk tenant (cms/loker/ikm)
 * agar optimasi core/rakaca tidak mempengaruhi cms.
 *
 * - Singleton terpisah dari Bale\Core\Services\MenuRegistry
 * - Hanya untuk type=tenant
 * - Tidak ada cache global dengan core — 0% risiko silang
 * - Optimasi: eager + cache per user per 5 menit (isolasi cms)
 */
class CmsMenuRegistry
{
    /** @var array<int, array> */
    protected array $groups = [];

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
        $userId = Auth::id() ?? 'guest';
        // v2: ikut pola core — cache BERISI seluruh array groups (bukan per-group).
        // Kunci sebelumnya (tanpa v2) menimbulkan bug: filterItems di-cache per group
        // sehingga hasil group pertama menimpa group berikutnya.
        $cacheKey = "cms.menu.{$type}.{$userId}.v2";

        if (Cache::has($cacheKey)) {
            $cached = Cache::get($cacheKey);
            if (is_array($cached)) {
                return $cached;
            }
        }

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

        Cache::put($cacheKey, $result, 300);

        return $result;
    }

    protected function filterItems(array $items): array
    {
        // Eager permission sekali per request (tanpa cache store — resolveGroups
        // yang bertanggung jawab atas cache hasil akhir seluruh groups).
        static $permsCache = [];
        $userId = Auth::id() ?? 'guest';
        if (! isset($permsCache[$userId])) {
            $user = Auth::user();
            if ($user) {
                $user->loadMissing('roles', 'permissions');
                $permsCache[$userId] = $user->getAllPermissions()->pluck('name')->toArray();
            } else {
                $permsCache[$userId] = [];
            }
        }
        $perms = $permsCache[$userId];

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
        try {
            Cache::flush();
        } catch (\Throwable $e) {
        }
    }
}

<?php

namespace Bale\Cms\Traits;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Services\TenantManager;

trait UsesTenantConnection
{
    /**
     * Eloquent akan memanggil method ini untuk menentukan connection model.
     * Kita ambil connection aktif dari TenantManager.
     */
    public function getConnectionName()
    {
        $active = TenantManager::getActiveConnection();

        if ($active) {
            return $active;
        }

        // Jangan pernah jatuh ke DB landlord: pulihkan tenant dari sesi.
        return app()->runningInConsole()
            ? config('database.default')
            : TenantConnectionService::resolveForQuery();
    }
}

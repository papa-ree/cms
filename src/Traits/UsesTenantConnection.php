<?php

namespace Bale\Cms\Traits;

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

        // jika tidak ada active tenant connection, fallback ke default
        return $active ?? config('database.default');
    }
}

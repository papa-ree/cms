<?php

namespace Bale\Cms\Models;

use Spatie\Permission\Models\Permission as SpatiePermission;
use Bale\Cms\Services\TenantConnectionService;

class Permission extends SpatiePermission
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Otomatis gunakan koneksi tenant yang sedang aktif
        $this->setConnection(TenantConnectionService::connection());
    }
}

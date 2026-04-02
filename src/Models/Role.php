<?php

namespace Bale\Cms\Models;

use Spatie\Permission\Models\Role as SpatieRole;
use Bale\Cms\Services\TenantConnectionService;

class Role extends SpatieRole
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Otomatis gunakan koneksi tenant yang sedang aktif
        $this->setConnection(TenantConnectionService::connection());
    }

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];
}

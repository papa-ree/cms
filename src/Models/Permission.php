<?php

namespace Bale\Cms\Models;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Core\Traits\LogsActivity;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use LogsActivity;

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

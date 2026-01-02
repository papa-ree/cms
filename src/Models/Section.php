<?php

namespace Bale\Cms\Models;

use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Services\TenantManager;
use Bale\Cms\Traits\UsesTenantConnection;

class Section extends Model
{
    use UsesTenantConnection;
    use HasUuids;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

}

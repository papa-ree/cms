<?php

namespace Bale\Cms\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Services\TenantManager;
use Bale\Cms\Traits\UsesTenantConnection;

class Navigation extends Model
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

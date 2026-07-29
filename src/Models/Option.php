<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasUuids;
    use LogsActivity;
    use UsesTenantConnection;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];
}

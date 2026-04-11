<?php

namespace Bale\Cms\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Cms\Traits\HasSeoMeta;

class Page extends Model
{
    use UsesTenantConnection;
    use HasUuids;
    use HasSeoMeta;

    protected $table = 'pages';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];
}

<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Traits\LogsActivity;
use Bale\Seo\Traits\HasSeoMeta;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasSeoMeta;
    use HasUuids;
    use LogsActivity;
    use UsesTenantConnection;

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

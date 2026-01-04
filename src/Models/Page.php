<?php

namespace Bale\Cms\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Traits\UsesTenantConnection;

class Page extends Model
{
    use UsesTenantConnection;
    use HasUuids;

    protected $table = 'pages';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->diffForHumans(),
        );
    }
}

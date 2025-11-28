<?php

namespace Bale\Cms\Models;

use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Services\TenantManager;
use Bale\Cms\Traits\UsesTenantConnection;

class Navigation extends Model
{
    use UsesTenantConnection;
    use HasUuids;

    protected static function booted(): void
    {
        static::deleted(function (Navigation $nav) {
            if ($nav->children()->exists()) {
                $nav->children()->update([
                    'parent_id' => null,
                ]);
            }
        });
    }

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    // Recursive relationship for child-parent navigation
    public function children()
    {
        return $this->hasMany(Navigation::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

}

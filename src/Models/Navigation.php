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
        $instance = $this->newRelatedInstance(Navigation::class);
        $instance->setConnection($this->getConnectionName());

        return $this->newHasMany(
            $instance->newQuery(),
            $this,
            $instance->getTable() . '.parent_id',
            'id'
        )->orderBy('order');
    }

    public function parent()
    {
        $instance = $this->newRelatedInstance(Navigation::class);
        $instance->setConnection($this->getConnectionName());

        return $this->newBelongsTo(
            $instance->newQuery(),
            $this,
            'parent_id',
            'id',
            'parent'
        );
    }

}

<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasUuids;
    use LogsActivity;
    use UsesTenantConnection;

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
        'content' => 'array',
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
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
            $instance->getTable().'.parent_id',
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

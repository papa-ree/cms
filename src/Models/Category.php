<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasUuids, UsesTenantConnection;

    protected $guarded = ['id'];

    /**
     * Relasi ke Post melalui category_slug
     */
    public function posts()
    {
        return $this->hasMany(Post::class, 'category_slug', 'slug');
    }
}

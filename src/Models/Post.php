<?php

namespace Bale\Cms\Models;

use Bale\Core\Support\Cdn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Traits\UsesTenantConnection;

class Post extends Model
{
    use UsesTenantConnection;
    use HasUuids;

    /**
     * Tentukan nama tabel.
     * Diasumsikan setiap tenant memiliki tabel 'posts'
     */
    protected $table = 'posts';

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

    /**
     * Relasi dengan user (berdasarkan UUID)
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_uuid', 'user_uuid');
    }

    protected function createdAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => Carbon::parse($value)->diffForHumans(),
        );
    }

    /**
     * Get CDN URL for thumbnail image
     * Format: https://cdn_url/cdn_prefix/organization_slug/thumbnails/filename
     */
    protected function thumbnailUrl(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->thumbnail) {
                    return null;
                }

                return Cdn::url('thumbnails/' . $this->thumbnail);
            }
        );
    }

    // /**
    //  * Scope untuk post yang sudah terbit
    //  */
    // public function scopePublished($query)
    // {
    //     return $query->where('status', 'published')
    //         ->whereNotNull('published_at')
    //         ->where('published_at', '<=', now());
    // }
}

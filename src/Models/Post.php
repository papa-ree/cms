<?php

namespace Bale\Cms\Models;

use Bale\Core\Support\Cdn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Seo\Traits\HasSeoMeta;

class Post extends Model
{
    use UsesTenantConnection;
    use HasUuids;
    use HasSeoMeta;

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
        'published' => 'boolean',
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
    ];

    protected static function booted()
    {
        static::deleting(function ($post) {
            $slug = session('bale_active_slug');
            if (!$slug) {
                return;
            }

            $disk = app()->isProduction() ? 's3' : 'public';

            // Hapus thumbnail
            if ($post->thumbnail) {
                \Illuminate\Support\Facades\Storage::disk($disk)->delete($slug . '/thumbnails/' . $post->thumbnail);
            }

            // Hapus image dari EditorJS content
            if (is_array($post->content) && isset($post->content['blocks'])) {
                foreach ($post->content['blocks'] as $block) {
                    if ($block['type'] === 'image' && isset($block['data']['file']['url'])) {
                        $url = $block['data']['file']['url'];
                        $path = parse_url($url, PHP_URL_PATH);
                        if ($path) {
                            $filename = basename($path);
                            if ($filename) {
                                \Illuminate\Support\Facades\Storage::disk($disk)->delete($slug . '/images/' . $filename);
                            }
                        }
                    }
                }
            }
        });
    }

    /**
     * Relasi dengan user (berdasarkan UUID)
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_uuid', 'user_uuid');
    }

    /**
     * Relasi dengan user (author) - Nama relasi dibedakan dari nama kolom 'author' di DB untuk menghindari bentrok/shadowing
     */
    public function userAuthor()
    {
        return $this->belongsTo(User::class, 'author', 'uuid');
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

    /**
     * Relasi ke Category melalui category_slug
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_slug', 'slug');
    }
}

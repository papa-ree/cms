<?php

namespace Bale\Cms\Models;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Core\Support\Cdn;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Bale\Cms\Services\TenantManager;
use Bale\Cms\Traits\UsesTenantConnection;

class Section extends Model
{
    use UsesTenantConnection;
    use HasUuids;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array', // otomatis konversi JSON ↔ array
    ];

    /**
     * Get backgrounds with CDN URLs
     * This accessor helps generate CDN URLs for background images in section content
     */
    protected function backgroundImages(): Attribute
    {
        return Attribute::make(
            get: function () {
                $content = $this->content;

                if (!isset($content['backgrounds']) || !is_array($content['backgrounds'])) {
                    return [];
                }

                return array_map(function ($background) {
                    if (isset($background['path'])) {
                        $background['cdn_url'] = Cdn::url('landing-page/' . $background['path']);
                    }
                    return $background;
                }, $content['backgrounds']);
            }
        );
    }
}

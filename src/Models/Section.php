<?php

namespace Bale\Cms\Models;

use Bale\Cms\Traits\UsesTenantConnection;
use Bale\Core\Support\Cdn;
use Bale\Core\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasUuids;
    use LogsActivity;
    use UsesTenantConnection;

    /**
     * Kolom yang boleh diisi (mass assignment)
     */
    protected $guarded = ['id'];

    protected $casts = [
        'content' => 'array',
        'created_at' => 'datetime:d M Y',
        'updated_at' => 'datetime:d M Y',
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

                if (! isset($content['backgrounds']) || ! is_array($content['backgrounds'])) {
                    return [];
                }

                return array_map(function ($background) {
                    if (isset($background['path'])) {
                        $background['cdn_url'] = Cdn::url('landing-page/'.$background['path']);
                    }

                    return $background;
                }, $content['backgrounds']);
            }
        );
    }
}

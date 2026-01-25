<?php

use Bale\Cms\Models\Option;
use Bale\Core\Support\Cdn;

if (!function_exists('organization_slug')) {
    /**
     * Get the organization slug from the options table.
     *
     * @return string|null
     */
    function organization_slug()
    {
        return Option::whereName('organization_slug')->first()?->value;
    }
}

if (!function_exists('cdn_asset')) {
    /**
     * Generate CDN URL untuk asset path.
     * Format: https://cdn.ponorogo.go.id/bale/organization-slug/path
     *
     * @param string $path
     * @return string
     */
    function cdn_asset(string $path): string
    {
        return Cdn::asset($path);
    }
}

if (!function_exists('cdn_url')) {
    /**
     * Generate CDN URL untuk path.
     * Alias untuk cdn_asset()
     *
     * @param string $path
     * @return string
     */
    function cdn_url(string $path): string
    {
        return Cdn::url($path);
    }
}

if (!function_exists('cdn_enabled')) {
    /**
     * Check apakah CDN aktif.
     * Mengambil nilai dari environment variable EMPERAN_CDN_ENABLED
     *
     * @return bool
     */
    function cdn_enabled(): bool
    {
        return Cdn::enabled();
    }
}

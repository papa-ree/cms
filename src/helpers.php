<?php

use Bale\Cms\Models\Option;

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

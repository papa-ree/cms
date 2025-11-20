<?php

namespace Bale\Cms\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bale\Cms\Cms
 */
class Cms extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bale\Cms\Cms::class;
    }
}

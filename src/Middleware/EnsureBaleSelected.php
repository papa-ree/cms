<?php

namespace Bale\Cms\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureBaleSelected
{
    public function handle(Request $request, Closure $next)
    {
        // Allow the select page itself
        if ($request->is('cms/select') || $request->is('cms/select/*')) {
            return $next($request);
        }

        if (!session()->has('bale_active_uuid')) {
            // route 'bale.cms.select' expected to be registered
            return redirect()->route('rakaca.select-bale');
        }

        return $next($request);
    }
}

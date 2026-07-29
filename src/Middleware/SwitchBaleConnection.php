<?php

namespace Bale\Cms\Middleware;

use Bale\Cms\Models\BaleUser;
use Bale\Cms\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchBaleConnection
{
    public function handle(Request $request, Closure $next)
    {
        $baleUuid = session('bale_active_uuid');
        $user = Auth::user();

        if (! $baleUuid) {
            // Let EnsureBaleSelected handle redirect
            return $next($request);
        }

        if (! $user?->uuid) {
            abort(403, 'Unauthorized.');
        }

        // Authorize: check pivot table that user_uuid is allowed on this bale
        $allowed = BaleUser::where('bale_id', $baleUuid)
            ->where('user_uuid', $user->uuid)
            ->exists();

        if (! $allowed) {
            abort(403, 'You do not have access to this Bale.');
        }

        // Initialize tenant connection (throws if cannot connect)
        try {
            TenantManager::initializeFromBaleUuid($baleUuid);
        } catch (\Throwable $e) {
            // prefer 500 to surface connection problems
            abort(500, 'Cannot connect to tenant database: '.$e->getMessage());
        }

        return $next($request);
    }
}

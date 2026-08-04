<?php

namespace Bale\Cms\Middleware;

use Bale\Cms\Models\BaleUser;
use Bale\Cms\Services\TenantManager;
use Closure;
use Illuminate\Contracts\Encryption\DecryptException;
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
            $errorMessage = __('Gagal menghubungkan ke database tenant: :message', ['message' => $e->getMessage()]);

            // Jika decrypt error (karena perbedaan APP_KEY)
            if ($e instanceof DecryptException || str_contains($e->getMessage(), 'MAC is invalid')) {
                $errorMessage = __('Gagal mendekripsi kredensial database tenant (Kemungkinan APP_KEY berbeda dengan server asal). Silakan edit dan perbarui password database.');
            }

            // Hapus session bale aktif agar tidak stuck loop
            session()->forget(['bale_active_uuid', 'bale_active_slug']);

            // Redirect berdasarkan permission user
            if ($user && $user->can('bale-list.update')) {
                return redirect()->route('rakaca.landlord.bale-list.index')
                    ->with('error', $errorMessage);
            }

            return redirect()->route('select-bale')
                ->with('error', $errorMessage);
        }

        return $next($request);
    }
}

<?php

namespace Bale\Cms\Services;

use Illuminate\Support\Facades\Auth;
use Bale\Cms\Models\BaleContentManagementBaleUser;
use Bale\Cms\Services\TenantManager;

class TenantConnectionService
{
    /**
     * Pastikan koneksi tenant aktif dan valid untuk user saat ini.
     */
    public static function ensureActive(): void
    {
        $baleUuid = session('bale_active_uuid');
        $user = Auth::user();

        if (!$baleUuid || !$user?->uuid) {
            throw new \RuntimeException('Session tenant tidak ditemukan atau user tidak valid.');
        }

        // Pastikan user punya akses ke bale
        $allowed = BaleContentManagementBaleUser::where('bale_id', $baleUuid)
            ->where('user_uuid', $user->uuid)
            ->exists();

        if (!$allowed) {
            abort(403, 'Anda tidak memiliki akses ke tenant ini.');
        }

        $active = TenantManager::getActiveConnection();
        $expected = TenantManager::connectionName($baleUuid);

        // Jika koneksi belum aktif atau berbeda, inisialisasi ulang
        if ($active !== $expected) {
            TenantManager::initializeFromBaleUuid($baleUuid);
        }
    }

    public static function connection(): string
    {
        $conn = TenantManager::getActiveConnection();

        if (!$conn) {
            throw new \Exception("No active tenant connection for validation.");
        }

        return $conn;
    }
}

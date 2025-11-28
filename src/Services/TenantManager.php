<?php

namespace Bale\Cms\Services;

use Illuminate\Support\Facades\DB;
use Bale\Cms\Models\BaleContentManagementBale;

class TenantManager
{
    /**
     * Nama koneksi aktif untuk request ini.
     */
    protected static ?string $activeConnection = null;

    /**
     * Inisialisasi koneksi dari bale UUID.
     *
     * @param  string  $baleUuid
     * @throws \RuntimeException
     */
    public static function initializeFromBaleUuid(string $uuid): void
    {
        // info("TenantManager.initializeFromBaleUuid called", ['bale_uuid' => $uuid]);

        $bale = BaleContentManagementBale::find($uuid);

        if (!$bale) {
            throw new \Exception("Bale with UUID $uuid not found");
        }

        // Generate connection name
        $connectionName = "bale_" . str_replace('-', '_', $uuid);

        // Tambahkan konfigurasi runtime
        config([
            "database.connections.{$connectionName}" => [
                'driver' => 'mysql',
                'host' => $bale->database_host,
                'database' => $bale->database_name,
                'username' => $bale->database_username,
                'password' => $bale->database_password,
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
            ],
        ]);

        // Simpan ke variabel static agar bisa diakses global
        self::$activeConnection = $connectionName;

        // Uji koneksi
        try {
            DB::connection($connectionName)->getPdo();
        } catch (\Throwable $e) {
            info("TenantManager failed to connect: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Mengambil koneksi aktif saat ini.
     */
    public static function getActiveConnection(): ?string
    {
        // info("getActiveConnection called", ['active' => self::$activeConnection]);
        return self::$activeConnection;
    }

    /**
     * Mendapatkan instance DB connection aktif.
     *
     * @return \Illuminate\Database\Connection|\Illuminate\Database\ConnectionInterface
     * @throws \RuntimeException
     */
    public static function connection()
    {
        if (!self::$activeConnection) {
            // throw new \RuntimeException("No active tenant connection found. Please initialize first.");
        }

        return DB::connection(self::$activeConnection);
    }

    /**
     * Membangun nama koneksi dari UUID Bale.
     */
    public static function connectionName(string $baleUuid): string
    {
        return 'bale_' . str_replace('-', '_', $baleUuid);
    }

    /**
     * Menghapus koneksi aktif saat ini.
     */
    public static function clear(): void
    {
        self::$activeConnection = null;
    }
}

<?php

namespace Bale\Cms\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Bale\Cms\Models\BaleContentManagementBale;

// class TenantManager
// {
//     // Nama koneksi aktif untuk request ini
//     protected static ?string $activeConnection = null;

//     /**
//      * Initialize connection from a given bale UUID.
//      * This method fetches credentials from landlord table bale_content_management_bales.
//      *
//      * @param string $baleUuid
//      * @throws \RuntimeException if cannot connect
//      */
//     public static function initializeFromBaleUuid(string $baleUuid): void
//     {
//         // if already initialized for same connection, skip
//         if (self::$activeConnection !== null && self::$activeConnection === self::connectionName($baleUuid)) {
//             return;
//         }

//         // Load bale record from landlord database (models under package use default connection)
//         $bale = BaleContentManagementBale::where('id', $baleUuid)->first();

//         if (!$bale) {
//             throw new \RuntimeException("Bale not found: {$baleUuid}");
//         }

//         // Compose unique connection name
//         $connName = self::connectionName($baleUuid);

//         // Set runtime connection config
//         Config::set("database.connections.{$connName}", [
//             'driver' => 'mysql',
//             'host' => $bale->database_host,
//             'port' => $bale->database_port ?? 3306,
//             'database' => $bale->database_name,
//             'username' => $bale->database_username,
//             'password' => $bale->database_password,
//             'charset' => 'utf8mb4',
//             'collation' => 'utf8mb4_unicode_ci',
//             'prefix' => '',
//             'prefix_indexes' => true,
//             'strict' => true,
//             'engine' => null,
//         ]);

//         // Purge and reconnect to ensure fresh connection
//         DB::purge($connName);

//         try {
//             DB::connection($connName)->getPdo();
//         } catch (\Throwable $e) {
//             // cleanup set config to avoid keeping invalid connection
//             Config::offsetUnset("database.connections.{$connName}");
//             throw new \RuntimeException("Cannot connect to tenant database: " . $e->getMessage(), 0, $e);
//         }

//         // Save active connection name for this request lifecycle
//         self::$activeConnection = $connName;
//     }

//     /**
//      * Return the active connection name (or null if none)
//      *
//      * @return string|null
//      */
//     public static function getActiveConnection(): ?string
//     {
//         return self::$activeConnection;
//     }

//     /**
//      * Build consistent connection name for a bale uuid
//      *
//      * @param string $baleUuid
//      * @return string
//      */
//     public static function connectionName(string $baleUuid): string
//     {
//         // Keep consistent naming, no unpredictable characters
//         return 'bale_' . str_replace('-', '_', $baleUuid);
//     }

//     /**
//      * Clear active connection for the current request (if needed)
//      */
//     public static function clear(): void
//     {
//         self::$activeConnection = null;
//     }
// }

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

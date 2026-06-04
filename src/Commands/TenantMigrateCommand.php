<?php

namespace Bale\Cms\Commands;

use Bale\Cms\Models\BaleList;
use Bale\Cms\Services\TenantManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class TenantMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:tenant-migrate {--tenant= : The slug of the tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run CMS migrations for a specific active tenant database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tenantSlug = $this->option('tenant');

        if (!$tenantSlug) {
            // Fetch only active tenants
            $tenants = BaleList::where('is_active', true)->get();
            if ($tenants->isEmpty()) {
                $this->error('No active tenants found in bale_lists table.');
                return self::FAILURE;
            }

            $tenantSlug = $this->choice(
                'Which active tenant database do you want to migrate?',
                $tenants->pluck('slug')->toArray()
            );
        }

        try {
            $tenant = BaleList::where('slug', $tenantSlug)->firstOrFail();

            if (!$tenant->is_active) {
                $this->warn("Warning: The selected tenant '{$tenant->name}' is not active, but proceeding anyway.");
            }

            $sourceDir = base_path('database/migrations/cms');
            
            // Fallback to package migration source if root doesn't exist
            if (!File::isDirectory($sourceDir)) {
                $sourceDir = __DIR__ . '/../../database/migrations/cms';
            }

            if (!File::isDirectory($sourceDir)) {
                $this->error("Migration source directory 'database/migrations/cms' not found.");
                return self::FAILURE;
            }

            $tempDir = base_path('database/migrations/cms_temp');

            // Clean previous temp dir if exists
            if (File::isDirectory($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            File::makeDirectory($tempDir, 0755, true);

            try {
                $files = File::files($sourceDir);
                $copiedCount = 0;

                foreach ($files as $file) {
                    $filename = $file->getFilename();
                    if (Str::endsWith($filename, '.stub')) {
                        $newFilename = Str::beforeLast($filename, '.stub');
                        if (!Str::endsWith($newFilename, '.php')) {
                            $newFilename .= '.php';
                        }
                    } else if (Str::endsWith($filename, '.php')) {
                        $newFilename = $filename;
                    } else {
                        continue;
                    }

                    File::copy($file->getPathname(), $tempDir . '/' . $newFilename);
                    $copiedCount++;
                }

                if ($copiedCount === 0) {
                    $this->error("No migrations found in {$sourceDir}");
                    return self::FAILURE;
                }

                $this->info("Initializing connection for tenant: {$tenant->slug}");
                TenantManager::initializeFromBaleUuid($tenant->id);
                $connection = TenantManager::getActiveConnection();

                if (!$connection) {
                    throw new \Exception("Failed to activate connection for tenant {$tenant->slug}");
                }

                $this->info("Migrating CMS tables (DB: {$tenant->database_name})...");

                $this->call('migrate', [
                    '--database' => $connection,
                    '--path'     => 'database/migrations/cms_temp',
                    '--force'    => true,
                ]);

                $this->info("Migration for tenant {$tenant->slug} completed successfully.");

                return self::SUCCESS;
            } finally {
                // Ensure temporary directory is cleaned up
                if (File::isDirectory($tempDir)) {
                    File::deleteDirectory($tempDir);
                }
            }
        } catch (Throwable $e) {
            $this->error("Migration failed: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}

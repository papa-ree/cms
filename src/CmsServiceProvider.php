<?php

namespace Bale\Cms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;
use Livewire\Livewire;
use Symfony\Component\Finder\Finder;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Method register()
     * 
     * Digunakan untuk mendaftarkan service, binding, atau command
     * ke dalam service container Laravel.
     */
    public function register(): void
    {
        $this->registerCommands();
    }

    protected function registerCommands(): void
    {
        $commands = [
            // 'command.cms:make-organisasi' => GenerateOrganisasiCommand::class,
            // 'command.cms:make-bale' => GenerateBaleCommand::class,
            // 'command.cms:make-user' => GenerateUserBaleCommand::class,
        ];

        foreach ($commands as $key => $class) {
            $this->app->bind($key, $class);
        }

        $this->commands(array_keys($commands));
    }

    /**
     * Method boot()
     * 
     * Dipanggil setelah semua service diregistrasi.
     * Digunakan untuk load resource seperti:
     * - view
     * - migration
     * - konfigurasi
     * - Livewire component
     */
    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->registerViews();
        $this->loadMigrations();
        $this->offerPublishing();
        $this->registerLivewireComponents();
    }

    /**
     * Load migration langsung dari package.
     * 
     * Dengan ini, user bisa langsung menjalankan migration
     * tanpa harus publish file ke aplikasi utama.
     */
    protected function loadMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    protected function registerViews(): void
    {
        $this->loadViewsFrom(
            __DIR__ . '/../resources/views',
            'cms'
        );
    }

    /**
     * Publish file agar bisa diubah oleh user.
     */

    protected function offerPublishing(): void
    {
        if (!$this->app->runningInConsole()) {
            return;
        }

        // Publish config
        $this->publishes([
            __DIR__ . '/../config/bale-cms.php' => config_path('bale-cms.php'),
        ], 'bale-cms:config');

        $this->publishes([
            __DIR__ . '/../resources/js/bale-cms.js' => resource_path('js/bale-cms.js'),
        ], 'bale-cms:assets');

        $this->publishes($this->getMigrations(), 'bale-cms:migrations');

    }

    /**
     * Mengambil semua file migration dari direktori package.
     */
    protected function getMigrations(): array
    {
        $migrations = [];
        $sourcePath = __DIR__ . '/../database/migrations/';

        // Pastikan direktori ada
        if (!is_dir($sourcePath)) {
            return $migrations;
        }

        // Loop semua file migration (baik .php maupun .stub)
        foreach (glob($sourcePath . '*.{php,stub}', GLOB_BRACE) as $file) {
            $filename = basename($file);

            // Jika file stub, ganti menjadi nama migration yang benar di aplikasi
            $targetFile = $this->getMigrationFileName($filename);

            $migrations[$file] = $targetFile;
        }

        return $migrations;
    }

    /**
     * Membuat nama file migration yang sesuai dengan timestamp laravel.
     */
    protected function getMigrationFileName(string $filename): string
    {
        $timestamp = date('Y_m_d_His');
        $migrationName = str_replace('.stub', '.php', $filename);

        return database_path('migrations/' . $timestamp . '_' . $migrationName);
    }

    /**
     * Registrasi semua Livewire Component yang ada di folder src/Livewire.
     * 
     * Mekanisme:
     * - Cari semua file PHP di dalam folder Livewire
     * - Pastikan class tersebut adalah turunan Livewire\Component
     * - Buat alias secara otomatis dari struktur folder
     * 
     * Contoh:
     *   src/Livewire/Dashboard.php
     *     => <livewire:bale-cms.dashboard />
     *
     */
    protected function registerLivewireComponents(): void
    {
        $namespace = "Bale\\Cms\\Livewire";
        $basePath = __DIR__ . "/Livewire";

        // Jika folder Livewire tidak ada, hentikan proses
        if (!is_dir($basePath)) {
            return;
        }

        $finder = new Finder();
        $finder->files()->in($basePath)->name('*.php');

        foreach ($finder as $file) {
            $relativePathname = $file->getRelativePathname();

            // Normalisasi path (Windows/Linux)
            $nsPath = str_replace(['/', '\\'], '\\', $relativePathname);

            // Konversi ke FQCN (Fully Qualified Class Name)
            $class = $namespace . '\\' . Str::beforeLast($nsPath, '.php');

            // Skip jika class tidak ditemukan
            if (!class_exists($class)) {
                continue;
            }

            // Skip jika bukan turunan Livewire\Component
            if (!is_subclass_of($class, LivewireComponent::class)) {
                continue;
            }

            // Buat alias berdasarkan struktur folder (kebab-case)
            $withoutExt = Str::replaceLast('.php', '', $relativePathname);
            $segments = preg_split('#[\\/\\\\]#', $withoutExt);
            $kebab = array_map(fn($s) => Str::kebab($s), $segments);

            $alias = 'cms.' . implode('.', $kebab);

            // Registrasi komponen ke Livewire
            Livewire::component($alias, $class);
        }
    }
}

<?php

namespace Bale\Cms\Commands;

use Bale\Cms\Models\BaleList;
use Bale\Cms\Models\Page;
use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantManager;
use Bale\Core\Services\ImageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class ConvertImagesToWebpCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:convert-images-to-webp
        {--tenant= : Slug tenant yang akan diproses (kosong = semua tenant aktif)}
        {--dry-run : Hanya laporan, tanpa mengubah file maupun database}
        {--keep-original : Pertahankan file asli setelah konversi}
        {--quality=80 : Kualitas output WebP (1-100)}
        {--max-width=1920 : Lebar maksimum output (proporsional, tidak diperbesar)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Konversi gambar CMS lama (thumbnail & og_image) menjadi WebP';

    /**
     * Execute the console command.
     */
    public function handle(ImageService $imageService): int
    {
        if (! $imageService->supportsWebp()) {
            $this->error('WebP encoding tidak didukung di server ini (butuh GD "imagewebp" atau Imagick).');

            return self::FAILURE;
        }

        $tenantsQuery = BaleList::where('is_active', true);
        if ($tenant = $this->option('tenant')) {
            $tenantsQuery = BaleList::where('slug', $tenant);
        }

        $tenants = $tenantsQuery->get();
        if ($tenants->isEmpty()) {
            $this->error('Tidak ada tenant aktif yang ditemukan.');

            return self::FAILURE;
        }

        $dryRun = (bool) $this->option('dry-run');
        $keepOriginal = (bool) $this->option('keep-original');
        $quality = (int) $this->option('quality');
        $maxWidth = (int) $this->option('max-width');

        $grandTotal = 0;
        $grandConverted = 0;
        $grandFailed = 0;
        $grandSkipped = 0;

        foreach ($tenants as $tenant) {
            $this->newLine();
            $this->info("► Tenant: {$tenant->slug} ({$tenant->name})");

            try {
                TenantManager::initializeFromBaleUuid($tenant->id);
                $connection = TenantManager::getActiveConnection();
            } catch (Throwable $e) {
                $this->error('  Gagal terhubung ke database tenant: '.$e->getMessage());
                continue;
            }

            $prefix = $tenant->slug.'/thumbnails/';
            $total = 0;
            $converted = 0;
            $failed = 0;
            $skipped = 0;

            foreach (Post::on($connection)->with('seoMeta')->get() as $post) {
                $status = $this->convertField($imageService, $post, 'thumbnail', $prefix, $quality, $maxWidth, $dryRun, $keepOriginal);
                $total += $status['total'];
                $converted += $status['converted'];
                $failed += $status['failed'];
                $skipped += $status['skipped'];

                if ($post->seoMeta) {
                    $status = $this->convertField($imageService, $post->seoMeta, 'og_image', $prefix, $quality, $maxWidth, $dryRun, $keepOriginal);
                    $total += $status['total'];
                    $converted += $status['converted'];
                    $failed += $status['failed'];
                    $skipped += $status['skipped'];
                }
            }

            foreach (Page::on($connection)->with('seoMeta')->get() as $page) {
                if ($page->seoMeta) {
                    $status = $this->convertField($imageService, $page->seoMeta, 'og_image', $prefix, $quality, $maxWidth, $dryRun, $keepOriginal);
                    $total += $status['total'];
                    $converted += $status['converted'];
                    $failed += $status['failed'];
                    $skipped += $status['skipped'];
                }
            }

            $this->line(sprintf(
                '  Selesai: %d diproses, %d dikonversi, %d gagal, %d sudah webp.',
                $total, $converted, $failed, $skipped
            ));

            $grandTotal += $total;
            $grandConverted += $converted;
            $grandFailed += $failed;
            $grandSkipped += $skipped;
        }

        $this->newLine();
        $this->info(sprintf(
            'Rekap: %d total, %d dikonversi, %d gagal, %d sudah webp.',
            $grandTotal, $grandConverted, $grandFailed, $grandSkipped
        ));

        return $grandFailed > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * Konversi satu field gambar (basename) menjadi WebP dan update database.
     *
     * @return array{total:int, converted:int, failed:int, skipped:int}
     */
    private function convertField(
        ImageService $imageService,
        object $model,
        string $field,
        string $prefix,
        int $quality,
        int $maxWidth,
        bool $dryRun,
        bool $keepOriginal,
    ): array {
        $value = $model->{$field};
        if (! $value) {
            return ['total' => 0, 'converted' => 0, 'failed' => 0, 'skipped' => 0];
        }

        $isWebp = Str::endsWith(strtolower($value), '.webp');
        $newBasename = Str::beforeLast($value, '.').'.webp';
        $oldPath = $prefix.$value;

        if ($isWebp) {
            return ['total' => 0, 'converted' => 0, 'failed' => 0, 'skipped' => 1];
        }

        $this->line('  ['.get_class($model).'] '.$oldPath.' → '.$newBasename);

        if ($dryRun) {
            return ['total' => 1, 'converted' => 0, 'failed' => 0, 'skipped' => 0];
        }

        $newPath = $imageService->convertExisting($oldPath, quality: $quality, maxWidth: $maxWidth);

        if (! $newPath) {
            $this->warn("    Gagal konversi {$oldPath}.");

            return ['total' => 1, 'converted' => 0, 'failed' => 1, 'skipped' => 0];
        }

        if (! $keepOriginal) {
            Storage::disk($imageService->disk())->delete($oldPath);
            $this->line('    File asli dihapus.');
        }

        // Hapus cache path baru (ini path baru tetap basename sama hanya ekstensi berubah)
        $model->{$field} = $newBasename;
        $model->save();

        return ['total' => 1, 'converted' => 1, 'failed' => 0, 'skipped' => 0];
    }
}
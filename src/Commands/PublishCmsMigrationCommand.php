<?php

namespace Bale\Cms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PublishCmsMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:publish-cms-migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish CMS migrations from package to application without changing timestamps';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $sourcePath = __DIR__ . '/../../database/migrations/cms';
        $targetPath = database_path('migrations/cms');

        if (!File::isDirectory($sourcePath)) {
            $this->error("Source directory not found: {$sourcePath}");
            return self::FAILURE;
        }

        if (!File::isDirectory($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
            $this->info("Created directory: {$targetPath}");
        }

        $files = File::files($sourcePath);
        $count = 0;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            
            // Only process .php.stub files
            if (!str_ends_with($filename, '.php.stub')) {
                continue;
            }

            $targetName = str_replace('.php.stub', '.php', $filename);
            $destination = $targetPath . DIRECTORY_SEPARATOR . $targetName;

            if (File::exists($destination)) {
                $this->line("File already exists, skipping: <comment>{$targetName}</comment>");
                continue;
            }

            File::copy($file->getPathname(), $destination);
            $this->info("Published: <info>{$targetName}</info>");
            $count++;
        }

        if ($count > 0) {
            $this->info("Successfully published {$count} migration(s).");
        } else {
            $this->info("No new migrations were published.");
        }

        return self::SUCCESS;
    }
}

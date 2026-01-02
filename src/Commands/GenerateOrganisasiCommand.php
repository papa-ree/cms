<?php

namespace Bale\Cms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Bale\Cms\Models\BaleContentManagementOrganization;

class GenerateOrganisasiCommand extends Command
{
    protected $signature = 'cms:make-organisasi 
                            {--name= : Nama organisasi}';

    protected $description = 'Generate organisasi baru untuk Bale Praban';

    public function handle(): int
    {
        $name = $this->option('name') ?? $this->ask('Masukkan nama organisasi');
        $slug = Str::of($name)->slug('-');

        $organization = BaleContentManagementOrganization::create([
            'name' => $name,
            'slug' => $slug,
        ]);

        $this->info("✅ Organisasi berhasil dibuat: {$organization->name}");

        return self::SUCCESS;
    }
}

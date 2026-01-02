<?php

namespace Bale\Cms\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Bale\Cms\Models\BaleList;
use Bale\Cms\Models\BaleContentManagementOrganization;

class GenerateBaleCommand extends Command
{
    protected $signature = 'cms:make-bale 
                            {--organization_slug= : Nama organisasi induk} 
                            {--name= : Nama bale} 
                            {--database= : Nama database tenant} 
                            {--host= : Host database} 
                            {--username= : Username database} 
                            {--password= : Password database} 
                            {--port=3306 : Port database}';

    protected $description = 'Generate bale baru untuk organisasi tertentu';

    public function handle(): int
    {
        $organizationName = $this->option('organization_slug') ?? $this->ask('Masukkan nama organisasi induk');
        $organization = BaleContentManagementOrganization::whereName($organizationName)->first();

        if (!$organization) {
            $this->error('❌ Organisasi tidak ditemukan.');
            return self::FAILURE;
        }

        $name = $this->option('name') ?? $this->ask('Masukkan nama bale');
        $database = $this->option('database') ?? $this->ask('Nama database tenant');
        $host = $this->option('host') ?? $this->ask('Host database', '127.0.0.1');
        $username = $this->option('username') ?? $this->ask('Username database', 'root');
        $password = $this->option('password') ?? $this->secret('Password database');
        $port = $this->option('port') ?? 3306;

        $bale = BaleList::create([
            'organization_id' => $organization->id,
            'name' => $name,
            'slug' => Str::of($name)->slug('-'),
            'database_host' => $host ?? '127.0.0.1',
            'database_name' => $database,
            'database_username' => $username ?? 'root',
            'database_password' => $password ?? '',
            'port' => $port,
            'is_active' => true,
        ]);

        $this->info("✅ Bale '{$bale->name}' berhasil dibuat untuk organisasi '{$organization->name}'");

        return self::SUCCESS;
    }
}

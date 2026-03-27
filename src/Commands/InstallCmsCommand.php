<?php

namespace Bale\Cms\Commands;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class InstallCmsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install CMS: Seed permissions';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting CMS installation...');

        $this->seedPermissions();

        $this->info('CMS installation completed successfully!');

        return self::SUCCESS;
    }

    protected function seedPermissions(): void
    {
        $this->info('Seeding permissions...');

        $permissions = [
            // CMS Permissions
            'bale-post.create',
            'bale-post.read',
            'bale-post.update',
            'bale-post.delete',
            'bale-page.create',
            'bale-page.read',
            'bale-page.update',
            'bale-page.delete',
            'bale-navigation.create',
            'bale-navigation.read',
            'bale-navigation.update',
            'bale-navigation.delete',
            'bale-section.create',
            'bale-section.read',
            'bale-section.update',
            'bale-section.delete',
            'bale-option.create',
            'bale-option.read',
            'bale-option.update',
            'bale-option.delete',
            'bale-user.create',
            'bale-user.read',
            'bale-user.update',
            'bale-user.delete',
            'bale-seo.create',
            'bale-seo.read',
            'bale-seo.update',
            'bale-seo.delete',
            'bale-category.create',
            'bale-category.read',
            'bale-category.update',
            'bale-category.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(['name' => $permission], ['guard_name' => 'web']);
        }

        $this->info('Permissions seeded and updated.');

        // Force sync to root role if exists
        $rootRole = Role::where('name', 'root')->first();
        if ($rootRole) {
            $this->info('Adding bale- permissions to root role...');
            $rootRole->givePermissionTo(Permission::where('name', 'like', 'bale-%')->get());

            // Clear cache
            app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

            $this->info('Bale permissions added and cache cleared for root role.');
        }

    }
}

<?php

/*
 | ---------------------------------------------------------------------------
 | Menu tenant (CMS) harus langsung mengikuti perubahan role/permission
 | ---------------------------------------------------------------------------
 |
 | CmsMenuRegistry dulu menyimpan hasil filter menu 5 menit per user, sehingga
 | perubahan role/permission baru terlihat setelah `optimize:clear`.
 |
 */

use App\Models\User;
use Bale\Cms\Services\CmsMenuRegistry;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

function freshCmsMenuRegistry(string $permission): CmsMenuRegistry
{
    $registry = new CmsMenuRegistry;

    (function () use ($permission) {
        $this->groups = [[
            'key' => 'test',
            'label' => 'Test',
            'icon' => 'box',
            '_type' => 'tenant',
            'items' => [[
                'label' => 'Fitur',
                'url' => 'fitur',
                'icon' => 'circle',
                'permission' => $permission,
            ]],
        ]];
    })->call($registry);

    return $registry;
}

it('menu tenant muncul begitu user di-assign role tanpa optimize:clear', function () {
    Permission::firstOrCreate(['name' => 'cms-menu-fresh.read', 'guard_name' => 'web']);
    $role = Role::firstOrCreate(['name' => 'cms-menu-fresh-role', 'guard_name' => 'web']);
    $role->givePermissionTo('cms-menu-fresh.read');

    $user = User::factory()->create();
    $this->actingAs($user);

    expect(freshCmsMenuRegistry('cms-menu-fresh.read')->getTenantGroups())->toBe([]);

    $user->assignRole($role);

    $groups = freshCmsMenuRegistry('cms-menu-fresh.read')->getTenantGroups();

    expect($groups)->toHaveCount(1)
        ->and($groups[0]['items'][0]['label'])->toBe('Fitur');
});

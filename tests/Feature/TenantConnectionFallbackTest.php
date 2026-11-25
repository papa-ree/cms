<?php

use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Services\TenantManager;

it('menolak query tenant tanpa sesi alih-alih membaca koneksi landlord', function () {
    TenantManager::clear();

    expect(fn () => TenantConnectionService::resolveForQuery())
        ->toThrow(RuntimeException::class, 'No active tenant session.');
});

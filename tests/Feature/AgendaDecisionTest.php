<?php

use App\Models\User;
use Bale\Cms\Livewire\Pages\Agenda\CreateNewAgenda;
use Bale\Cms\Livewire\Pages\Agenda\Section\AgendaTable;
use Bale\Cms\Models\Agenda;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Services\TenantManager;
use Bale\Cms\Traits\HasSafeDelete;
use Bale\Core\Livewire\SharedComponents\ItemActions;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Livewire\Livewire;

/**
 * TODO-A502 — Lima keputusan desain (PLAN-AGENDA §A5.2).
 *
 * Dijalankan sebagai test Feature di app suite (root tests/Pest.php
 * mengikat package Feature dir ke Tests\TestCase + RefreshDatabase).
 */
it('builder: RRULE tidak pernah memuat DTSTART (anchor di start_date)', function () {
    $cmp = new CreateNewAgenda;
    $cmp->is_recurring = true;
    $cmp->frequency = 'monthly';
    $cmp->monthly_mode = 'nth';
    $cmp->weeklyDays = ['MO'];
    $cmp->nthPositions = ['1', '3'];
    $cmp->start_date = '2026-10-05 09:00';

    $rrule = $cmp->buildRrule();

    expect($rrule)->toBeString();
    expect(strtoupper($rrule))->not->toContain('DTSTART');
    expect($rrule)->toStartWith('FREQ=MONTHLY');
});

it('builder: non-recurring menghasilkan rrule null', function () {
    $cmp = new CreateNewAgenda;
    $cmp->is_recurring = false;

    expect($cmp->buildRrule())->toBeNull();
});

it('builder: "Senin ke-1 & ke-3" → FREQ=MONTHLY;BYDAY=MO;BYSETPOS=1,3', function () {
    $cmp = new CreateNewAgenda;
    $cmp->is_recurring = true;
    $cmp->frequency = 'monthly';
    $cmp->monthly_mode = 'nth';
    $cmp->weeklyDays = ['MO'];
    $cmp->nthPositions = ['1', '3'];
    $cmp->start_date = '2026-10-05 09:00';

    expect($cmp->buildRrule())->toBe('FREQ=MONTHLY;BYDAY=MO;BYSETPOS=1,3');
});

it('hapus item-actions mendispatch deleteAgenda, bukan deleteItem', function () {
    Livewire::test(ItemActions::class, [
        'deleteId' => 'agenda-123',
        'deleteEvent' => 'deleteAgenda',
    ])->call('delete')
        ->assertDispatched('deleteAgenda', id: 'agenda-123')
        ->assertNotDispatched('deleteItem');
});

it('AgendaTable hanya mendengar deleteAgenda dan satu klik menghapus satu record', function () {
    // --- Wiring: komponen punya #[On('deleteAgenda')] + HasSafeDelete + model Agenda.
    $table = new AgendaTable;
    expect(class_uses_recursive($table))->toHaveKey(HasSafeDelete::class);
    expect(new ReflectionClass($table)->getProperty('modelClass')->getValue($table))
        ->toBe(Agenda::class);

    $on = new ReflectionClass($table)->getMethod('deleteAgenda');
    $attributes = $on->getAttributes(\Livewire\Attributes\On::class);
    expect(count($attributes))->toBe(1);
    expect($attributes[0]->newInstance()->event)->toBe('deleteAgenda');

    // --- Environment tenant nyata (tapi menunjuk sqlite) supaya performDelete
    // --- lewat TenantConnectionService betulan (bukan double-delete generic).
    $uuid = (string) Str::uuid();
    $tenantConn = 'bale_'.str_replace('-', '_', $uuid);
    config()->set("database.connections.{$tenantConn}", [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);

    Schema::connection($tenantConn)->create('agendas', function (Blueprint $t) {
        $t->uuid('id')->primary();
        $t->string('title');
        $t->text('description')->nullable();
        $t->string('location')->nullable();
        $t->timestamp('start_date');
        $t->timestamp('end_date')->nullable();
        $t->string('category')->nullable();
        $t->boolean('is_visible')->default(true);
        $t->boolean('all_day')->default(false);
        $t->string('timezone')->nullable();
        $t->boolean('is_recurring')->default(false);
        $t->string('rrule')->nullable();
        $t->timestamps();
    });
    Schema::connection($tenantConn)->create('activity_log', function (Blueprint $t) {
        $t->bigIncrements('id');
        $t->string('log_name')->nullable();
        $t->text('description')->nullable();
        $t->nullableMorphs('subject');
        $t->nullableMorphs('causer');
        $t->longText('properties')->nullable();
        $t->uuid('batch_uuid')->nullable();
        $t->timestamps();
    });

    $user = User::factory()->create([
        'uuid' => (string) Str::uuid(),
    ]);
    DB::table('bale_lists')->insert([
        'id' => $uuid,
        'slug' => 'agenda-test',
        'name' => 'Agenda Test',
        'database_host' => 'localhost',
        'database_name' => 'agenda_test',
        'database_username' => 'test',
        'database_password' => 'test',
        'storage_prefix' => null,
        'is_active' => true,
        'organization_id' => (string) Str::uuid(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('bale_users')->insert([
        'id' => (string) Str::uuid(),
        'bale_id' => $uuid,
        'user_uuid' => $user->uuid,
        'role' => 'root',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $agendaId = (string) Str::uuid();
    DB::connection($tenantConn)->table('agendas')->insert([
        'id' => $agendaId,
        'title' => 'Rapat rutin',
        'description' => null,
        'location' => 'Ruang B',
        'start_date' => now(),
        'end_date' => null,
        'category' => null,
        'is_visible' => true,
        'all_day' => false,
        'timezone' => 'Asia/Jakarta',
        'is_recurring' => false,
        'rrule' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    session(['bale_active_uuid' => $uuid]);
    Auth::login($user);

    $ref = new ReflectionClass(TenantManager::class);
    $prop = $ref->getProperty('activeConnection');
    $prop->setAccessible(true);
    $prop->setValue(null, $tenantConn);

    try {
        expect(TenantConnectionService::connection())->toBe($tenantConn);

        Livewire::test(AgendaTable::class)
            ->call('deleteAgenda', $agendaId)
            ->assertDispatched('toast');

        expect(DB::connection($tenantConn)->table('agendas')->count())->toBe(0);
    } finally {
        $prop->setValue(null, null);
        Auth::logout();
    }
});

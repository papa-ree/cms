<?php

namespace Bale\Cms\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Bale\Cms\Models\BaleList;
use Bale\Cms\Models\BaleUser;

class GenerateUserBaleCommand extends Command
{
    protected $signature = 'cms:make-user
                            {--bale_slug= : Nama Bale yang akan dikaitkan}
                            {--nip= : NIP user di database utama}';

    protected $description = 'Generate relasi user dengan Bale berdasarkan NIP user di database utama';

    public function handle(): int
    {
        $baleSlug = $this->option('bale_slug') ?? $this->ask('Masukkan Slug Bale');
        $nip = $this->option('nip') ?? $this->ask('Masukkan NIP user');

        $bale = BaleList::whereName($baleSlug)->first();

        if (!$bale) {
            $this->error('❌ Bale tidak ditemukan.');
            return self::FAILURE;
        }

        // Ambil user dari database utama (default connection)
        $user = User::whereUsername($nip)->first();

        if (!$user) {
            $this->error("❌ User dengan NIP {$nip} tidak ditemukan di database utama.");
            return self::FAILURE;
        }

        // Cek apakah sudah ada relasi
        $exists = BaleUser::whereBaleId($bale->id)
            ->whereUserUuid($user->uuid)
            ->exists();

        if ($exists) {
            $this->warn("⚠️  User '{$user->name}' sudah terhubung dengan Bale '{$bale->name}'.");
            return self::SUCCESS;
        }

        // Buat relasi baru
        BaleUser::create([
            'bale_id' => $bale->id,
            'user_uuid' => $user->uuid,
        ]);

        $this->info("✅ User '{$user->name}' (NIP: {$nip}) berhasil dikaitkan dengan Bale '{$bale->name}'.");

        return self::SUCCESS;
    }
}

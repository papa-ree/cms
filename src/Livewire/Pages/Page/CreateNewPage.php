<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\{Layout};
use Bale\Cms\Models\Page;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
class CreateNewPage extends Component
{
    public $title;
    public $slug;

    public function rules()
    {
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        return [
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.pages', 'slug'),
            ],
        ];
    }

    public function render()
    {
        return view('cms::livewire.pages.page.create-new-page');
    }

    public function store(LivewireAlert $alert, $slug)
    {
        $this->slug = $slug['slug'];

        // jalankan validasi tenant-aware
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            // gunakan koneksi tenant saat create
            $page = (new Page)
                ->setConnection($connection)
                ->create([
                    'title' => $this->title,
                    'slug' => $this->slug,
                ]);

            DB::commit();

            session()->flash('saved', [
                'title' => 'New Page Created!',
            ]);

            $this->redirectRoute('bale.cms.pages.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page creation failed: ' . $th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }
}
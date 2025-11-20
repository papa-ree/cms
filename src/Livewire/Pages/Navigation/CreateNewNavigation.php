<?php

namespace Bale\Cms\Livewire\Pages\Navigation;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\{Layout};
use Bale\Cms\Models\Navigation;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
class CreateNewNavigation extends Component
{
    public $name;

    public $slug;

    public function render()
    {
        return view('cms::livewire.pages.navigation.create-new-navigation');
    }

    public function rules()
    {
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        return [
            'title' => ['required', 'string', 'min:3', 'max:20'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.navigations', 'slug'),
            ],
        ];
    }

    public function store(LivewireAlert $alert, $slug)
    {
        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            // gunakan koneksi tenant saat create
            $nav = (new Navigation)
                ->setConnection($connection)
                ->create([
                    'name' => $this->title,
                    'slug' => $this->slug,
                ]);
            DB::commit();

            session()->flash('saved', [
                'title' => 'New Navigation Created!',
            ]);

            $this->redirectRoute('bale.cms.navigations.index', $nav->id, navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page creation failed: ' . $th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }
}
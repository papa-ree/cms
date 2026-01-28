<?php

namespace Bale\Cms\Livewire\Pages\Navigation;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Bale\Cms\Models\Navigation;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
#[Title('Bale | Create Navigation')]
class CreateNewNavigation extends Component
{
    public $name;

    public $slug;

    #[Locked]
    public $parent_slug;

    #[Locked]
    public $parent_id;

    public function mount($parent)
    {
        if ($parent) {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();
            $parent = (new Navigation)
                ->setConnection($connection)
                ->whereSlug($parent)
                ->first();

            $this->parent_id = $parent->id ?? null;
            $this->parent_slug = $parent->slug ?? null;

        }
    }

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
            'name' => ['required', 'string', 'min:4', 'max:30'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.navigations', 'slug'),
            ],
        ];
    }

    public function store($data)
    {
        $this->name = $data['name'] ?? $this->name;
        $this->slug = $data['slug'] ?? $this->slug;
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
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'parent_id' => $this->parent_id,
                    'order' => 99,
                    'actived' => true,
                ]);
            DB::commit();

            session()->flash('success', 'New Navigation Created!');

            if ($this->parent_slug == null) {
                $this->redirectRoute('bale.cms.navigations.edit', $this->slug, navigate: true);
            } else {
                $this->redirectRoute('bale.cms.navigations.edit', $this->parent_slug ?? $this->slug, navigate: true);
            }



        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}
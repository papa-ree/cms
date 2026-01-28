<?php

namespace Bale\Cms\Livewire\Pages\Navigation;

use Bale\Cms\Livewire\Pages\Navigation\Section\NavigationSortable;
use Bale\Cms\Livewire\SharedComponents\DeleteModelAction;
use Bale\Cms\Models\Navigation;
use Bale\Cms\Models\Page;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, Locked, Title};
use Livewire\Livewire;

#[Layout('cms::layouts.app')]
#[Title('Bale | Edit Navigation')]
class EditNavigation extends Component
{
    use HasSafeDelete;

    protected string $modelClass = Navigation::class;

    #[Locked]
    public $id;
    public $name;
    public $slug;
    public $url_mode;
    public $url;
    public $page_slug = '';
    public $parent;

    #[Locked]
    public $delete_nav_id;

    public function mount($slug)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $nav = (new Navigation)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->first();

        $this->id = $nav->id;
        $this->name = $nav->name;
        $this->slug = $nav->slug;
        $this->url_mode = $nav->url_mode;
        $this->url = $nav->url;
        $this->page_slug = $nav->page_slug;
        $this->parent = null;

        // Load parent navigation if exists (for breadcrumb)
        // Store as array to avoid Eloquent model serialization issues with tenant connection
        if ($nav->parent_id) {
            $parentNav = (new Navigation)
                ->setConnection($connection)
                ->find($nav->parent_id);

            if ($parentNav) {
                $this->parent = [
                    'id' => $parentNav->id,
                    'name' => $parentNav->name,
                    'slug' => $parentNav->slug,
                ];
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.navigation.edit-navigation');
    }

    #[Computed]
    public function availablePages()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();
        $pages = (new Page)->setConnection($connection)
            ->query()
            ->select('id', 'title', 'slug')->get();
        return $pages;
    }

    #[Computed]
    public function availableChilds()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();
        $pages = (new Navigation)
            ->setConnection($connection)
            ->query()
            ->whereParentId($this->id)
            ->orderBy('order')
            ->select('id', 'name', 'slug')->get();
        return $pages;
    }

    public function sortItem($itemId, $newPosition)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $parent = (new Navigation)
            ->setConnection($connection)
            ->find($itemId)->parent_id;

        // Ambil semua nav yang memiliki parent sama dalam urutan sekarang
        $items = (new Navigation)
            ->setConnection($connection)
            ->whereParentId($parent)
            ->orderBy('order')
            ->get();

        // Ubah collection ke array index-based
        $itemsArray = $items->values();

        // Temukan index item yang dipindah
        $currentIndex = $itemsArray->search(fn($i) => $i->id == $itemId);

        // Ambil itemnya
        $movedItem = $itemsArray->pull($currentIndex);

        // Sisipkan di posisi baru
        $itemsArray->splice($newPosition, 0, [$movedItem]);

        // Reorder semua item
        foreach ($itemsArray as $index => $item) {
            $item->setConnection($connection)
                ->update(['order' => $index]);
        }
    }

    public function reorderChildren($ids)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        foreach ($ids as $index => $id) {
            (new Navigation)
                ->setConnection($connection)
                ->where('id', $id)
                ->update(['order' => $index]);
        }

        $this->dispatch('toast', message: 'Sub-navigation reordered!', type: 'success');
        $this->dispatch('navigation-reordered');
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
                Rule::unique($connection . '.navigations', 'slug')->ignore($this->id),
            ],
            'url_mode' => ['required', 'boolean'],
            'url' => ['required_if:url_mode,true', 'required_if:url_mode,1', 'max:255'],
            'page_slug' => ['required_if:url_mode,false', 'required_if:url_mode,0', 'max:255'],
        ];
    }

    public function update($data)
    {
        $this->name = $data['name'] ?? $this->name;
        $this->slug = $data['slug'] ?? $this->slug;
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            $nav = (new Navigation)
                ->setConnection($connection)
                ->find($this->id)
                ->update([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'url_mode' => $this->url_mode,
                    'url' => $this->url,
                    'page_slug' => $this->page_slug,
                ]);
            DB::commit();

            $this->dispatch('toast', message: 'Data berhasil disimpan!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}
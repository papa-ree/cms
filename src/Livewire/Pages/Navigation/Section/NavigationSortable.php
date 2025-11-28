<?php

namespace Bale\Cms\Livewire\Pages\Navigation\Section;

use Bale\Cms\Models\Navigation;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, Locked, On};

#[Layout('cms::layouts.app')]
class NavigationSortable extends Component
{
    use HasSafeDelete;
    protected string $modelClass = Navigation::class;

    public $nav_parent;
    public $parentId;

    #[Locked]
    public $delete_nav_id;

    public function mount($navItemMode)
    {
        $this->nav_parent = $navItemMode;
    }

    public function render()
    {
        return view('cms::livewire.pages.navigation.section.navigation-sortable');
    }

    #[Computed]
    public function availableNavigations()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        // return (new Navigation)->setConnection($connection)->orderBy('order')->get();
        return (new Navigation)->setConnection($connection)->when($this->nav_parent, function ($query) {
            return $query->whereParentId($this->nav_parent);
        }, function ($query) {
            return $query->with('children')->whereNull('parent_id');
        })
            ->orderBy('order')
            ->get();
    }

    public function sortItem($itemId, $newPosition)
    {
        // dd($itemId, $newPosition);
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        // Ambil semua nav dalam urutan sekarang
        $items = (new Navigation)
            ->setConnection($connection)
            ->whereParentId(null)
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

    public function sortItemChild($itemId, $newPosition)
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
}
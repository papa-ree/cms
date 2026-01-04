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

        return (new Navigation)->setConnection($connection)
            ->when($this->nav_parent, function ($query) {
                return $query->whereParentId($this->nav_parent);
            }, function ($query) {
                return $query->with([
                    'children' => function ($q) {
                        $q->orderBy('order');
                    }
                ])->whereNull('parent_id');
            })
            ->orderBy('order')
            ->get();
    }

    public function reorderParents($ids)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        foreach ($ids as $index => $id) {
            (new Navigation)
                ->setConnection($connection)
                ->where('id', $id)
                ->update(['order' => $index]);
        }

        $this->dispatch('toast', message: 'Parent order updated!', type: 'success');
        $this->dispatch('navigation-reordered');
    }

    public function reorderChildren($newParentId, $toIds, $oldParentId = null, $fromIds = [])
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        // Update target container
        $parentId = ($newParentId === 'null' || $newParentId === '') ? null : $newParentId;
        foreach ($toIds as $index => $id) {
            (new Navigation)->setConnection($connection)->where('id', $id)->update([
                'parent_id' => $parentId,
                'order' => $index
            ]);
        }

        // Update source container if moved between different parents
        if ($oldParentId && $oldParentId !== $newParentId) {
            $fromParentId = ($oldParentId === 'null' || $oldParentId === '') ? null : $oldParentId;
            foreach ($fromIds as $index => $id) {
                (new Navigation)->setConnection($connection)->where('id', $id)->update([
                    'parent_id' => $fromParentId,
                    'order' => $index
                ]);
            }
        }

        $this->dispatch('toast', message: 'Navigation updated!', type: 'success');
        $this->dispatch('navigation-reordered');
    }
}
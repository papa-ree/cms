<?php

namespace Bale\Cms\Livewire\Pages\Navigation\Section;

use Bale\Cms\Models\Navigation;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, Locked, On, Async};

#[Layout('cms::layouts.app')]
class NavigationSortable extends Component
{
    use HasSafeDelete;
    protected string $modelClass = Navigation::class;

    public $nav_parent;
    public $parentId;

    #[Locked]
    public $delete_nav_id;

    // State management (Alpine.js handles pending changes)
    public bool $isSaving = false;

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

    #[Async]
    public function saveAllChanges($parentOrder = [], $childrenData = [])
    {
        $this->isSaving = true;

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            // Save parent order
            if (!empty($parentOrder)) {
                foreach ($parentOrder as $index => $id) {
                    $nav = (new Navigation)->setConnection($connection)->find($id);
                    if ($nav) {
                        $nav->order = $index;
                        $nav->save();
                    }
                }
            }

            // Save children data
            // Structure: [{ parentId: '1', childIds: ['2', '3'] }, ...]
            if (!empty($childrenData)) {
                foreach ($childrenData as $group) {
                    $parentId = $group['parentId'] ?? null;
                    $childIds = $group['childIds'] ?? [];

                    // Handle null/empty parent ID
                    $normalizedParentId = ($parentId === 'null' || $parentId === '') ? null : $parentId;

                    foreach ($childIds as $index => $childId) {
                        $nav = (new Navigation)->setConnection($connection)->find($childId);
                        if ($nav) {
                            $nav->parent_id = $normalizedParentId;
                            $nav->order = $index;
                            $nav->save();
                        }
                    }
                }
            }

            $this->dispatch('toast', message: 'Changes saved successfully!', type: 'success');
            // $this->redirectRoute('bale.cms.navigations.index', navigate: true);

        } catch (\Exception $e) {
            $this->dispatch('toast', message: 'Failed to save changes: ' . $e->getMessage(), type: 'error');
        } finally {
            $this->isSaving = false;
        }
    }

    public function resetChanges()
    {
        $this->dispatch('toast', message: 'Changes discarded', type: 'info');
    }
}
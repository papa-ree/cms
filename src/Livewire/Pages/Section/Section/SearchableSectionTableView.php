<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, Locked, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | View Searchable Section')]
class SearchableSectionTableView extends Component
{
    #[Locked]
    public $sectionSlug;
    public $sectionName;

    public $availableKeys = [];
    public $searchQuery = '';

    public function mount($slug)
    {
        $this->sectionSlug = $slug;

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->first();

        $this->sectionName = $section->name;

        // Extract available keys
        $content = $section->content ?? [];
        $items = $content['items'] ?? [];
        $meta = $content['meta'] ?? [];

        $sysKeys = ['id', 'created_at', 'updated_at'];
        $orderedKeys = $meta['order'] ?? [];
        $orderedKeys = array_values(array_diff($orderedKeys, $sysKeys));

        $itemKeys = [];
        if (count($items) > 0) {
            $itemKeys = array_keys($items[0]);
            $itemKeys = array_values(array_diff($itemKeys, $sysKeys));
        }

        $remainingKeys = array_diff($itemKeys, $orderedKeys);
        $this->availableKeys = array_values(array_merge($orderedKeys, $remainingKeys));
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-section-table-view');
    }

    public function updatingSearchQuery()
    {
        // Can be used for pagination reset if needed
    }

    #[Computed]
    public function filteredItems()
    {
        // $content = $this->section->content ?? [];
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($this->sectionSlug)
            ->first();

        $content = $section->content ?? [];
        $items = $content['items'] ?? [];

        if (empty($this->searchQuery)) {
            return $items;
        }

        // Search across all key values
        return array_filter($items, function ($item) {
            foreach ($item as $key => $values) {
                // Handle both array and string values
                $searchableText = is_array($values) ? implode(' ', $values) : $values;

                if (stripos($searchableText, $this->searchQuery) !== false) {
                    return true;
                }
            }
            return false;
        });
    }

    public function deleteItem(string $itemId)
    {
        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($this->sectionSlug)
                ->first();

            $content = $section->content ?? [];
            $items = $content['items'] ?? [];

            // Find item by id instead of index
            $items = array_values(array_filter($items, function ($item) use ($itemId) {
                $id = $item['id'][0] ?? $item['id'] ?? null;
                return $id !== $itemId;
            }));

            // Update content
            $content['items'] = $items;
            $section->update(['content' => $content]);

            DB::commit();
            $this->dispatch('toast', message: 'Item deleted successfully!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            info('Item delete failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Failed to delete item!', type: 'error');
        }
    }
}

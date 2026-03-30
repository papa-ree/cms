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

        $sysKeys = ['id', 'created_at', 'updated_at', 'uploads'];
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
                if ($key === 'uploads') continue;

                // Handle both array and string values
                if (is_array($values)) {
                    // Check if it's an array of arrays (like uploads, although we skip it above)
                    // or an array of strings (social links, tags, etc.)
                    $searchableValues = array_filter($values, fn($v) => is_string($v) || is_numeric($v));
                    $searchableText = implode(' ', $searchableValues);
                } else {
                    $searchableText = (string) $values;
                }

                if (stripos($searchableText, $this->searchQuery) !== false) {
                    return true;
                }
            }
            return false;
        });
    }

    public function deleteItem(string $itemId)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        DB::connection($connection)->beginTransaction();

        try {
            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($this->sectionSlug)
                ->first();

            if (!$section) {
                throw new \Exception('Section not found');
            }

            $content = $section->content ?? [];
            $items = $content['items'] ?? [];

            // Find item by id or index to match Blade logic
            $items = array_values(array_filter($items, function ($item, $idx) use ($itemId) {
                $id = $item['id'][0] ?? $item['id'] ?? (string) $idx;
                return (string) $id !== (string) $itemId;
            }, ARRAY_FILTER_USE_BOTH));

            // Update content
            $content['items'] = $items;
            $section->update(['content' => $content]);

            DB::connection($connection)->commit();
            $this->dispatch('toast', message: 'Item deleted successfully!', type: 'success');

        } catch (\Throwable $th) {
            DB::connection($connection)->rollBack();
            info('Item delete failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Failed to delete item!', type: 'error');
        }
    }
}

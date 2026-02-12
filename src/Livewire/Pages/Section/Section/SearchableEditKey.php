<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Edit Section Keys')]
class SearchableEditKey extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $name = '';

    #[Locked]
    public $slug = '';

    public $availableKeys = [];
    public $newKey = '';
    public $meta = [];

    public function mount($slug)
    {
        $this->slug = $slug;

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->first();

        if (!$section) {
            session()->flash('error', 'Section not found');
            return $this->redirectRoute('bale.cms.sections.index', navigate: true);
        }

        $this->id = $section->id;
        $this->name = $section->name;
        $this->slug = $section->slug;

        $content = $section->content ?? [];
        $this->meta = $content['meta'] ?? [];
        $items = $content['items'] ?? [];

        $sysKeys = ['id', 'created_at', 'updated_at'];
        $orderedKeys = $this->meta['order'] ?? [];
        $orderedKeys = array_values(array_diff($orderedKeys, $sysKeys));

        $itemKeys = [];
        if (count($items) > 0) {
            $itemKeys = array_keys($items[0]);
            $itemKeys = array_values(array_diff($itemKeys, $sysKeys));
        }

        $remainingKeys = array_diff($itemKeys, $orderedKeys);
        $this->availableKeys = array_values(array_merge($orderedKeys, $remainingKeys));
    }

    public function updateOrder($orderedKeys)
    {
        $this->availableKeys = $orderedKeys;
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-edit-key');
    }

    public function addKey()
    {
        // Validate key is not empty and doesn't already exist
        $this->newKey = trim($this->newKey);

        if (!$this->newKey || in_array($this->newKey, $this->availableKeys) || in_array($this->newKey, ['id', 'created_at', 'updated_at'])) {
            return;
        }

        $this->availableKeys[] = $this->newKey;
        $this->newKey = '';
    }

    public function removeKey($index)
    {
        if (isset($this->availableKeys[$index])) {
            unset($this->availableKeys[$index]);
            $this->availableKeys = array_values($this->availableKeys);
        }
    }

    public function save($keys = [])
    {
        // Update available keys from argument if provided
        if (!empty($keys)) {
            $this->availableKeys = $keys;
        }

        // Validate at least one key exists
        if (count($this->availableKeys) === 0) {
            $this->dispatch('toast', message: 'Please add at least one key!', type: 'error');
            return;
        }

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->find($this->id);

            // Get existing content
            $content = $section->content ?? [];

            // Update items to match new keys structure
            $items = $content['items'] ?? [];

            // If no items exist, create one empty item to persist the keys
            if (count($items) === 0) {
                $emptyItem = [];
                foreach ($this->availableKeys as $key) {
                    $emptyItem[$key] = [];
                }
                $items = [$emptyItem];
            } else {
                $updatedItems = [];
                foreach ($items as $item) {
                    $newItem = [];

                    // Preserve system keys
                    foreach (['id', 'created_at', 'updated_at'] as $sysKey) {
                        if (isset($item[$sysKey])) {
                            $newItem[$sysKey] = $item[$sysKey];
                        }
                    }

                    // Add user-defined keys
                    foreach ($this->availableKeys as $key) {
                        // Keep existing values if key exists, otherwise empty array
                        $newItem[$key] = $item[$key] ?? [];
                    }
                    $updatedItems[] = $newItem;
                }
                $items = $updatedItems;
            }

            // Save key order to meta
            if (!isset($content['meta'])) {
                $content['meta'] = [];
            }
            $content['meta']['order'] = $this->availableKeys;
            $content['items'] = $items;

            $section->update(['content' => $content]);

            DB::commit();

            $this->dispatch('toast', message: 'Keys updated successfully!', type: 'success');

            // Redirect back to edit form
            // $this->redirectRoute('bale.cms.sections.view-searchable', $this->slug, navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            info('Keys update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something went wrong!', type: 'error');
        }
    }
}

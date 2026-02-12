<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Manage Item')]
class SearchableCreateItem extends Component
{
    #[Locked]
    public $id;

    #[Locked]
    public $name = '';

    #[Locked]
    public $slug = '';

    #[Locked]
    public $itemIndex = null;

    public $availableKeys = [];
    public $currentItem = [];
    public $tempInputs = [];
    public $editMode = false;

    public function mount($slug, $itemIndex = null)
    {
        $this->slug = $slug;
        $this->itemIndex = $itemIndex;
        $this->editMode = $itemIndex !== null;

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
        $items = $content['items'] ?? [];

        $sysKeys = ['id', 'created_at', 'updated_at'];
        $orderedKeys = $content['meta']['order'] ?? [];
        $orderedKeys = array_values(array_diff($orderedKeys, $sysKeys));

        $itemKeys = [];
        if (count($items) > 0) {
            $itemKeys = array_keys($items[0]);
            $itemKeys = array_values(array_diff($itemKeys, $sysKeys));
        }

        $remainingKeys = array_diff($itemKeys, $orderedKeys);
        $this->availableKeys = array_values(array_merge($orderedKeys, $remainingKeys));

        if (count($this->availableKeys) === 0) {
            session()->flash('error', 'Please add keys first before creating items');
            return $this->redirectRoute('bale.cms.sections.edit-searchable-keys', $slug, navigate: true);
        }

        // If edit mode, load existing item data
        if ($this->editMode) {
            if (!isset($items[$itemIndex])) {
                session()->flash('error', 'Item not found');
                return $this->redirectRoute('bale.cms.sections.view-searchable', $slug, navigate: true);
            }

            $this->currentItem = $items[$itemIndex];

            // Ensure all values are arrays
            foreach ($this->currentItem as $key => &$value) {
                if (!is_array($value)) {
                    $value = $value !== '' ? [$value] : [];
                }
            }
        } else {
            // Initialize empty item with all keys
            foreach ($this->availableKeys as $key) {
                $this->currentItem[$key] = [];
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-create-item');
    }

    public function addValue($key)
    {
        // Validate input is not empty
        if (!isset($this->tempInputs[$key]) || $this->tempInputs[$key] === '') {
            return;
        }

        // Ensure array structure exists
        if (!isset($this->currentItem[$key])) {
            $this->currentItem[$key] = [];
        }

        // Convert to array if it's still a string
        if (!is_array($this->currentItem[$key])) {
            $this->currentItem[$key] = $this->currentItem[$key] !== ''
                ? [$this->currentItem[$key]]
                : [];
        }

        // Add new value
        $this->currentItem[$key][] = $this->tempInputs[$key];

        // Clear temporary input
        $this->tempInputs[$key] = '';
    }

    public function removeValue($key, $valueIndex)
    {
        // Ensure it's an array
        if (!is_array($this->currentItem[$key])) {
            return;
        }

        // Remove value
        unset($this->currentItem[$key][$valueIndex]);

        // Re-index array
        $this->currentItem[$key] = array_values($this->currentItem[$key]);
    }

    public function updateValue($key, $valueIndex, $newValue)
    {
        // Ensure it's an array
        if (!is_array($this->currentItem[$key])) {
            return;
        }

        if (isset($this->currentItem[$key][$valueIndex])) {
            $this->currentItem[$key][$valueIndex] = $newValue;
        }
    }

    public function save($data = [])
    {
        // Update current item from alpine data if provided
        if (!empty($data)) {
            $this->currentItem = $data;
        }

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->id);

            $content = $section->content ?? [];
            $items = $content['items'] ?? [];

            if ($this->editMode) {
                // Update generated timestamps
                $this->currentItem['updated_at'] = [now()->toDateTimeString()];

                // Ensure created_at exists
                if (!isset($this->currentItem['created_at'])) {
                    $this->currentItem['created_at'] = [now()->toDateTimeString()];
                }

                // Ensure id exists
                if (!isset($this->currentItem['id'])) {
                    $this->currentItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];
                }

                // Update existing item
                $items[$this->itemIndex] = $this->currentItem;
                $message = 'Item updated successfully!';
            } else {
                // Set generated timestamps
                $now = now()->toDateTimeString();
                $this->currentItem['created_at'] = [$now];
                $this->currentItem['updated_at'] = [$now];

                // Generate UUID
                $this->currentItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];

                // Add new item
                $items[] = $this->currentItem;
                $message = 'Item created successfully!';
            }

            $content['items'] = $items;

            $section->update(['content' => $content]);

            DB::commit();

            $this->dispatch('toast', message: $message, type: 'success');

            // Redirect to view table
            $this->redirectRoute('bale.cms.sections.view-searchable', $this->slug, navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            info('Item save failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something went wrong!', type: 'error');
        }
    }
}

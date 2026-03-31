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
    public const SOCIAL_PLATFORMS = [
        'facebook', 'instagram', 'youtube', 'whatsapp', 'tiktok',
        'twitter', 'x', 'linkedin', 'telegram', 'pinterest',
        'snapchat', 'threads', 'line', 'wechat'
    ];


    #[Locked]
    public $id;

    #[Locked]
    public $name = '';

    #[Locked]
    public $slug = '';

    #[Locked]
    public $itemId = null;

    public $availableKeys = [];
    public $currentItem = [];
    public $tempInputs = [];
    public $editMode = false;

    /** Toggle to show/hide the Upload Zone for this section */
    public bool $enableUpload = false;

    /** Toggle to show/hide the Social Media fields for this section */
    public bool $enableSocial = false;
    public array $activeSocialPlatforms = [];

    /** Becomes true after item is first saved in create mode, revealing the upload section */
    public bool $showUploadSection = false;

    public function mount($slug, $itemId = null)
    {
        $this->slug = $slug;
        $this->itemId = $itemId;
        $this->editMode = $itemId !== null;

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

        $sysKeys = ['id', 'created_at', 'updated_at', 'uploads', 'attachments'];
        $this->enableUpload = $content['meta']['enable_upload'] ?? false;
        $this->enableSocial = $content['meta']['enable_social'] ?? false;
        $this->activeSocialPlatforms = $content['meta']['social_platforms'] ?? [];

        // Add social media keys to sysKeys to avoid double rendering
        foreach ($this->activeSocialPlatforms as $platform) {
            $sysKeys[] = 'sm_' . $platform;
        }

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
            return $this->redirectRoute('bale.cms.sections.edit-keys', $slug, navigate: true);
        }

        if ($this->editMode) {
            $foundItem = null;
            foreach ($items as $item) {
                $id = $item['id'][0] ?? $item['id'] ?? null;
                if ($id === $this->itemId) {
                    $foundItem = $item;
                    break;
                }
            }

            if ($foundItem === null) {
                session()->flash('error', 'Item not found');
                return $this->redirectRoute('bale.cms.sections.view-searchable', $slug, navigate: true);
            }

            $this->currentItem = $foundItem;

            foreach ($this->currentItem as $key => &$value) {
                if (!is_array($value)) {
                    $value = $value !== '' ? [$value] : [];
                }
            }
            $this->showUploadSection = true;
        } else {
            foreach ($this->availableKeys as $key) {
                $this->currentItem[$key] = [];
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-create-item', [
            'fileKeys'           => $this->getFileKeys(),
            'socialKeys'         => $this->getSocialKeys(),
            'showUploadSection'  => $this->showUploadSection || $this->editMode,
        ]);
    }

    /**
     * Managed by the simplified "Universal Upload Zone" toggle.
     * Always uses the 'attachments' key if enabled.
     */
    public function getFileKeys(): array
    {
        return $this->enableUpload ? ['attachments'] : [];
    }

    /**
     * Managed by the "Social Media" toggle and platform selection.
     */
    public function getSocialKeys(): array
    {
        if (!$this->enableSocial) {
            return [];
        }
        
        return array_map(fn($p) => 'sm_' . $p, $this->activeSocialPlatforms);
    }

    public function addValue($key)
    {
        if (!isset($this->tempInputs[$key]) || $this->tempInputs[$key] === '') {
            return;
        }

        if (!isset($this->currentItem[$key])) {
            $this->currentItem[$key] = [];
        }

        if (!is_array($this->currentItem[$key])) {
            $this->currentItem[$key] = $this->currentItem[$key] !== ''
                ? [$this->currentItem[$key]]
                : [];
        }

        $this->currentItem[$key][] = $this->tempInputs[$key];
        $this->tempInputs[$key] = '';
    }

    public function removeValue($key, $valueIndex)
    {
        if (!is_array($this->currentItem[$key])) {
            return;
        }

        unset($this->currentItem[$key][$valueIndex]);
        $this->currentItem[$key] = array_values($this->currentItem[$key]);
    }

    public function updateValue($key, $valueIndex, $newValue)
    {
        if (!is_array($this->currentItem[$key])) {
            return;
        }

        if (isset($this->currentItem[$key][$valueIndex])) {
            $this->currentItem[$key][$valueIndex] = $newValue;
        }
    }

    /**
     * Save non-file field data (called from Alpine via the Save button).
     * File keys are intentionally excluded from the $data merge so that URLs
     * already auto-saved by persistFileChange() are never overwritten.
     */
    public function save($data = [])
    {
        if (!empty($data)) {
            $fileKeys = $this->getFileKeys();
            foreach ($data as $key => $value) {
                if (!in_array($key, $fileKeys)) {
                    $this->currentItem[$key] = $value;
                }
            }
        }

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        DB::connection($connection)->beginTransaction();

        try {
            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->id);

            $content = $section->content ?? [];
            $items = $content['items'] ?? [];

            if ($this->editMode) {
                $this->currentItem['updated_at'] = [now()->toDateTimeString()];

                if (!isset($this->currentItem['created_at'])) {
                    $this->currentItem['created_at'] = [now()->toDateTimeString()];
                }

                if (!isset($this->currentItem['id'])) {
                    $this->currentItem['id'] = [$this->itemId ?? \Illuminate\Support\Str::uuid()->toString()];
                }

                foreach ($items as $i => $item) {
                    $id = $item['id'][0] ?? $item['id'] ?? null;
                    if ($id === $this->itemId) {
                        $items[$i] = $this->currentItem;
                        break;
                    }
                }

                $content['items'] = $items;
                $section->update(['content' => $content]);
                DB::connection($connection)->commit();

                $this->dispatch('toast', message: 'Item updated successfully!', type: 'success');
                $this->redirectRoute('bale.cms.sections.view-searchable', $this->slug, navigate: true);

            } else {
                // Create mode: save item, stay on page so user can upload files
                $now   = now()->toDateTimeString();
                $newId = \Illuminate\Support\Str::uuid()->toString();

                $this->currentItem['created_at'] = [$now];
                $this->currentItem['updated_at'] = [$now];
                $this->currentItem['id']         = [$newId];

                $items[] = $this->currentItem;
                $content['items'] = $items;
                $section->update(['content' => $content]);

                DB::connection($connection)->commit();

                // Switch to edit mode and reveal the upload section
                $this->itemId            = $newId;
                $this->editMode          = true;
                $this->showUploadSection = true;

                $fileKeys = $this->getFileKeys();
                if (empty($fileKeys)) {
                    // No file keys — redirect immediately
                    $this->dispatch('toast', message: 'Item created successfully!', type: 'success');
                    $this->redirectRoute('bale.cms.sections.view-searchable', $this->slug, navigate: true);
                } else {
                    $this->dispatch('toast', message: 'Item tersimpan! Silakan upload file di bawah.', type: 'success');
                }
            }

        } catch (\Throwable $th) {
            DB::connection($connection)->rollBack();
            info('Item save failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something went wrong!', type: 'error');
        }
    }
}

<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Core\Support\Cdn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Livewire\WithFileUploads;

#[Layout('cms::layouts.app')]
#[Title('Bale | Manage Item')]
class SearchableCreateItem extends Component
{
    use WithFileUploads;

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

    // File upload
    public $tempUpload = [];
    public $activeUploadKey = '';

    // Upload auto-save status: null | 'saving' | 'saved'
    public $saveStatus = null;

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
        } else {
            foreach ($this->availableKeys as $key) {
                $this->currentItem[$key] = [];
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-create-item', [
            'fileKeys' => $this->getFileKeys(),
            'socialKeys' => $this->getSocialKeys(),
            'orgSlug' => session('bale_active_slug', ''),
        ]);
    }

    /**
     * Returns keys that should render a file uploader.
     */
    public function getFileKeys(): array
    {
        $patterns = ['images', 'files', 'attachments', 'documents', 'photos', 'gallery'];
        $suffixes = ['_image', '_images', '_file', '_files', '_foto', '_fotos', '_doc', '_docs', '_pdf', '_photo', '_photos', '_attachment', '_gambar'];

        return array_values(array_filter($this->availableKeys, function ($key) use ($patterns, $suffixes) {
            if (in_array($key, $patterns))
                return true;
            foreach ($suffixes as $suffix) {
                if (str_ends_with($key, $suffix))
                    return true;
            }
            return false;
        }));
    }

    /**
     * Returns keys that should render a Social Media URL input.
     */
    public function getSocialKeys(): array
    {
        $platforms = [
            'facebook', 'instagram', 'youtube', 'whatsapp', 'tiktok',
            'twitter', 'x', 'linkedin', 'telegram', 'pinterest',
            'snapchat', 'threads', 'line', 'wechat',
        ];

        $suffixes = [];
        foreach ($platforms as $p) {
            $suffixes[] = '_' . $p;
        }
        $suffixes[] = '_sosmed';
        $suffixes[] = '_social';

        $prefixes = ['social_', 'sosmed_'];

        return array_values(array_filter($this->availableKeys, function ($key) use ($platforms, $suffixes, $prefixes) {
            if (in_array($key, $this->getFileKeys())) {
                return false;
            }
            if (in_array($key, $platforms)) {
                return true;
            }
            foreach ($suffixes as $suffix) {
                if (str_ends_with($key, $suffix)) {
                    return true;
                }
            }
            foreach ($prefixes as $prefix) {
                if (str_starts_with($key, $prefix)) {
                    return true;
                }
            }
            return false;
        }));
    }

    /**
     * Triggered by Livewire after a file is uploaded via $wire.upload('tempUpload', ...).
     * Immediately stores the file to S3 and persists the URL to the database (auto-save).
     */
    public function updatedTempUpload()
    {
        if (empty($this->tempUpload))
            return;

        $this->saveStatus = 'saving';

        try {
            $files = is_array($this->tempUpload) ? $this->tempUpload : [$this->tempUpload];

            foreach ($files as $file) {
                if (!is_object($file)) continue;

                $extension = $file->getClientOriginalExtension();
                $fileName = $this->slug . '-' . uniqid() . '.' . $extension;
                $s3Path = session('bale_active_slug') . '/landing-page/items/' . $this->slug . "/" . $fileName;

                Storage::disk('s3')->put($s3Path, $file->get());

                $cdnUrl = Cdn::url('landing-page/items/' . $this->slug . "/" . $fileName);
                $origName = $file->getClientOriginalName();
                $mime = $file->getMimeType();

                $this->dispatch('file-uploaded', [
                    'key'    => $this->activeUploadKey,
                    'url'    => $cdnUrl,
                    'name'   => $origName,
                    'mime'   => $mime,
                    's3Path' => $s3Path,
                ]);

                if (!isset($this->currentItem[$this->activeUploadKey])) {
                    $this->currentItem[$this->activeUploadKey] = [];
                }
                if (!in_array($cdnUrl, $this->currentItem[$this->activeUploadKey])) {
                    $this->currentItem[$this->activeUploadKey][] = $cdnUrl;
                }
            }

            $this->persistFileChange();
            $this->saveStatus = 'saved';

        } catch (\Throwable $th) {
            info('SearchableCreateItem file upload failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Upload failed: ' . $th->getMessage(), type: 'error');
            $this->saveStatus = null;
        } finally {
            $this->tempUpload = [];
        }
    }

    /**
     * Delete a file from S3 and remove its URL from the item data. Auto-persists.
     */
    public function deleteFile(string $key, string $url, string $s3Path): void
    {
        $this->saveStatus = 'saving';
        try {
            if ($s3Path) {
                Storage::disk('s3')->delete($s3Path);
            }

            if (isset($this->currentItem[$key]) && is_array($this->currentItem[$key])) {
                $this->currentItem[$key] = array_values(
                    array_filter($this->currentItem[$key], fn($u) => $u !== $url)
                );
            }

            $this->persistFileChange();
            $this->saveStatus = 'saved';
            $this->dispatch('toast', message: 'File deleted.', type: 'success');

        } catch (\Throwable $th) {
            info('SearchableCreateItem deleteFile failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Failed to delete file: ' . $th->getMessage(), type: 'error');
            $this->saveStatus = null;
        }
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
     * Auto-save currentItem to the database after a file upload or delete.
     * In create mode: inserts the item and switches to edit mode so subsequent
     * uploads are associated with the same record.
     */
    private function persistFileChange(): void
    {
        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->id);

            $content = $section->content ?? [];
            $items = $content['items'] ?? [];

            if ($this->editMode && $this->itemId !== null) {
                $this->currentItem['updated_at'] = [now()->toDateTimeString()];

                if (!isset($this->currentItem['created_at'])) {
                    $this->currentItem['created_at'] = [now()->toDateTimeString()];
                }
                if (!isset($this->currentItem['id'])) {
                    $this->currentItem['id'] = [$this->itemId];
                }

                foreach ($items as $i => $item) {
                    $id = $item['id'][0] ?? $item['id'] ?? null;
                    if ($id === $this->itemId) {
                        $items[$i] = $this->currentItem;
                        break;
                    }
                }
            } else {
                $now = now()->toDateTimeString();
                $this->currentItem['created_at'] = [$now];
                $this->currentItem['updated_at'] = [$now];
                $newId = \Illuminate\Support\Str::uuid()->toString();
                $this->currentItem['id'] = [$newId];

                $items[] = $this->currentItem;

                $this->itemId = $newId;
                $this->editMode = true;
            }

            $content['items'] = $items;
            $section->update(['content' => $content]);

        } catch (\Throwable $th) {
            info('SearchableCreateItem auto-save failed: ' . $th->getMessage());
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
                $message = 'Item updated successfully!';
            } else {
                $now = now()->toDateTimeString();
                $this->currentItem['created_at'] = [$now];
                $this->currentItem['updated_at'] = [$now];
                $this->currentItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];
                $items[] = $this->currentItem;
                $message = 'Item created successfully!';
            }

            $content['items'] = $items;
            $section->update(['content' => $content]);

            DB::connection($connection)->commit();

            $this->dispatch('toast', message: $message, type: 'success');
            $this->redirectRoute('bale.cms.sections.view-searchable', $this->slug, navigate: true);

        } catch (\Throwable $th) {
            DB::connection($connection)->rollBack();
            info('Item save failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something went wrong!', type: 'error');
        }
    }
}

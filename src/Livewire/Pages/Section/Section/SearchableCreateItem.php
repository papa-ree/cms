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
    public $itemIndex = null;

    public $availableKeys = [];
    public $currentItem = [];
    public $tempInputs = [];
    public $editMode = false;

    // File upload
    public $tempUpload;
    public $activeUploadKey = '';

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
        return view('cms::livewire.pages.section.section.searchable-create-item', [
            'fileKeys' => $this->getFileKeys(),
            'socialKeys' => $this->getSocialKeys(),
            'orgSlug' => session('bale_active_slug', ''),
        ]);
    }

    /**
     * Returns keys that should render a FilePond uploader.
     * Matches common naming patterns for image/file fields.
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
     * Matches exact platform names, _suffix patterns, and social_ prefix patterns.
     */
    public function getSocialKeys(): array
    {
        $platforms = [
            'facebook',
            'instagram',
            'youtube',
            'whatsapp',
            'tiktok',
            'twitter',
            'x',
            'linkedin',
            'telegram',
            'pinterest',
            'snapchat',
            'threads',
            'line',
            'wechat',
        ];

        $suffixes = [];
        foreach ($platforms as $p) {
            $suffixes[] = '_' . $p;
        }
        // generic social suffixes
        $suffixes[] = '_sosmed';
        $suffixes[] = '_social';

        $prefixes = ['social_', 'sosmed_'];

        return array_values(array_filter($this->availableKeys, function ($key) use ($platforms, $suffixes, $prefixes) {
            // already tagged as file key → skip
            if (in_array($key, $this->getFileKeys())) {
                return false;
            }
            // exact platform name
            if (in_array($key, $platforms)) {
                return true;
            }
            // suffix match
            foreach ($suffixes as $suffix) {
                if (str_ends_with($key, $suffix)) {
                    return true;
                }
            }
            // prefix match
            foreach ($prefixes as $prefix) {
                if (str_starts_with($key, $prefix)) {
                    return true;
                }
            }
            return false;
        }));
    }

    /**
     * Triggered by FilePond after uploading a file via $wire.upload('tempUpload', ...).
     * Skips validate() intentionally — causes "Unable to retrieve file_size" on S3 temp disk.
     * Client-side validation is handled by FilePond plugins.
     */
    public function updatedTempUpload()
    {
        if (!$this->tempUpload)
            return;

        try {
            $file = $this->tempUpload;

            $extension = $file->getClientOriginalExtension();
            $fileName = $this->slug . '-' . uniqid() . '.' . $extension;
            $s3Path = session('bale_active_slug') . '/landing-page/items/' . $this->slug . "/" . $fileName;

            // Use Storage::put() directly — same approach as SectionMetaEditor (avoids S3 temp disk issues)
            Storage::disk('s3')->put($s3Path, $file->get());

            $cdnUrl = Cdn::url('landing-page/items/' . $this->slug . "/" . $fileName);
            $mime = $file->getMimeType();
            $origName = $file->getClientOriginalName();

            $this->dispatch('file-uploaded', [
                'key' => $this->activeUploadKey,
                'url' => $cdnUrl,
                'name' => $origName,
                'mime' => $mime,
                's3Path' => $s3Path,
            ]);

            // Push URL into currentItem so persistFileChange() saves it
            if (!isset($this->currentItem[$this->activeUploadKey])) {
                $this->currentItem[$this->activeUploadKey] = [];
            }
            if (!in_array($cdnUrl, $this->currentItem[$this->activeUploadKey])) {
                $this->currentItem[$this->activeUploadKey][] = $cdnUrl;
            }

            $this->persistFileChange();

        } catch (\Throwable $th) {
            info('SearchableCreateItem file upload failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Upload failed: ' . $th->getMessage(), type: 'error');
        } finally {
            $this->tempUpload = null;
        }
    }

    /**
     * Delete a file from S3 and remove its URL from the item data.
     * Called from Alpine when user clicks the remove button on an uploaded file card.
     */
    public function deleteFile(string $key, string $url, string $s3Path): void
    {
        try {
            // Skip exists() check — it throws "Unable to check existence" on first S3 request
            // after Livewire navigation (S3 client not yet fully booted).
            // Storage::delete() on a non-existent key is a no-op on S3, so this is safe.
            if ($s3Path) {
                Storage::disk('s3')->delete($s3Path);
            }

            // Remove the URL from currentItem so it's excluded on next save
            if (isset($this->currentItem[$key]) && is_array($this->currentItem[$key])) {
                $this->currentItem[$key] = array_values(
                    array_filter($this->currentItem[$key], fn($u) => $u !== $url)
                );
            }

            $this->dispatch('toast', message: 'File deleted.', type: 'success');

            $this->persistFileChange();

        } catch (\Throwable $th) {
            info('SearchableCreateItem deleteFile failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Failed to delete file: ' . $th->getMessage(), type: 'error');
        }
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

    /**
     * Auto-save currentItem to the database after a file upload or delete.
     * Does NOT redirect. In create mode, inserts the item and switches to edit mode.
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

            if ($this->editMode && $this->itemIndex !== null) {
                // Edit mode: update existing item in-place
                $this->currentItem['updated_at'] = [now()->toDateTimeString()];

                if (!isset($this->currentItem['created_at'])) {
                    $this->currentItem['created_at'] = [now()->toDateTimeString()];
                }
                if (!isset($this->currentItem['id'])) {
                    $this->currentItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];
                }

                $items[$this->itemIndex] = $this->currentItem;

            } else {
                // Create mode: insert new item and switch to edit mode
                $now = now()->toDateTimeString();
                $this->currentItem['created_at'] = [$now];
                $this->currentItem['updated_at'] = [$now];
                $this->currentItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];

                $items[] = $this->currentItem;

                // Switch to edit mode so subsequent auto-saves update this same item
                $this->itemIndex = array_key_last($items);
                $this->editMode = true;
            }

            $content['items'] = $items;
            $section->update(['content' => $content]);

        } catch (\Throwable $th) {
            info('SearchableCreateItem auto-save failed: ' . $th->getMessage());
            // Silent — don't show error toast for background auto-save
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

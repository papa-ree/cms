<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Core\Support\Cdn;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\Locked;
use Livewire\WithFileUploads;

/**
 * SectionItemUpload
 *
 * A self-contained Livewire component responsible for managing file uploads
 * for an already-saved searchable section item.
 *
 * Upload data is stored inside the item's `uploads` key (not the file key itself),
 * using a structured array of objects:
 *   [
 *     { item_id, url, name, original_name, size, mime_type, file_type, path, key, uploaded_at }
 *   ]
 *
 * Props (passed from parent):
 *   - slug     : string  — section slug
 *   - itemId   : string  — UUID of the saved item
 *   - fileKeys : array   — list of keys that should show an uploader
 */
class SectionItemUpload extends Component
{
    use WithFileUploads;

    #[Locked]
    public string $slug = '';

    #[Locked]
    public string $itemId = '';

    /** @var array<string> */
    public array $fileKeys = [];

    /** Livewire temporary upload holder (associative array by key) */
    public $tempUpload = [];

    /** null | 'saving' | 'saved' */
    public ?string $saveStatus = null;

    // ─── Mount ───────────────────────────────────────────────────────────────

    public function mount(string $slug, string $itemId, array $fileKeys = []): void
    {
        $this->slug     = $slug;
        $this->itemId   = $itemId;
        $this->fileKeys = $fileKeys;
    }

    // ─── Render ──────────────────────────────────────────────────────────────

    public function render()
    {
        return view('cms::livewire.pages.section.section.section-item-upload', [
            'uploads' => $this->getUploads(),
        ]);
    }

    // ─── Public helpers ───────────────────────────────────────────────────────

    /**
     * Read the current uploads array for this item from the database.
     * Grouped by `key` so the view can render per-field galleries.
     *
     * @return array<string, array>  e.g. ['images' => [...], 'photos' => [...]]
     */
    public function getUploads(): array
    {
        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->getSectionId());

            $items   = $section->content['items'] ?? [];
            $uploads = [];

            foreach ($items as $item) {
                $id = $item['id'][0] ?? $item['id'] ?? null;
                if ($id === $this->itemId) {
                    $raw = $item['uploads'] ?? [];
                    // Group by key
                    foreach ($raw as $entry) {
                        $key = !empty($entry['key']) ? $entry['key'] : ($this->fileKeys[0] ?? 'files');
                        $uploads[$key][] = $entry;
                    }
                    break;
                }
            }

            return $uploads;
        } catch (\Throwable) {
            return [];
        }
    }

    // ─── Livewire lifecycle ───────────────────────────────────────────────────

    /**
     * Triggered automatically by Livewire when `tempUpload` changes
     * (i.e., after a file is selected / dropped in the upload zone).
     */
    public function updatedTempUpload(): void
    {
        if (empty($this->tempUpload)) {
            return;
        }

        $this->saveStatus = 'saving';

        try {
            foreach ($this->tempUpload as $uploadKey => $fileData) {
                if (empty($fileData)) continue;

                $files = is_array($fileData) ? $fileData : [$fileData];

                foreach ($files as $file) {
                    if (!is_object($file)) continue;

                    $extension    = $file->getClientOriginalExtension();
                    $originalName = $file->getClientOriginalName();
                    $mime         = $file->getMimeType() ?: ($file->getClientMimeType() ?: '');
                    $size         = $file->getSize(); // bytes
                    $fileType     = $this->resolveFileType($mime, $extension);
                    $fileName     = $this->slug . '-' . uniqid() . '.' . $extension;
                    $orgSlug      = session('bale_active_slug', '');
                    $s3Path       = $orgSlug . '/landing-page/items/' . $this->slug . '/' . $fileName;

                    Storage::disk('s3')->put($s3Path, $file->get());

                    $cdnUrl = Cdn::url('landing-page/items/' . $this->slug . '/' . $fileName);

                    $entry = [
                        'item_id'       => $this->itemId,
                        'url'           => $cdnUrl,
                        'name'          => $fileName,
                        'original_name' => $originalName,
                        'size'          => $size,
                        'mime_type'     => $mime,
                        'file_type'     => $fileType,
                        'path'          => $s3Path,
                        'key'           => $uploadKey,
                        'uploaded_at'   => now()->toDateTimeString(),
                    ];

                    $this->persistUpload($entry);

                    $this->dispatch('upload-saved', [
                        'key'           => $uploadKey,
                        'url'           => $cdnUrl,
                        'name'          => $fileName,
                        'original_name' => $originalName,
                        'mime'          => $mime,
                        'size'          => $size,
                        'file_type'     => $fileType,
                        's3Path'        => $s3Path,
                    ]);
                }
            }

            $this->saveStatus = 'saved';

        } catch (\Throwable $th) {
            info('SectionItemUpload upload failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Upload gagal: ' . $th->getMessage(), type: 'error');
            $this->saveStatus = null;
        } finally {
            $this->tempUpload = [];
        }
    }

    /**
     * Delete an upload entry by its index inside a specific key's group.
     */
    public function deleteUpload(string $key, int $index): void
    {
        $this->saveStatus = 'saving';

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->getSectionId());

            $content = $section->content ?? [];
            $items   = $content['items'] ?? [];

            foreach ($items as $i => $item) {
                $id = $item['id'][0] ?? $item['id'] ?? null;
                if ($id !== $this->itemId) continue;

                $uploads = $item['uploads'] ?? [];

                // Find the nth entry with this key
                $nth = -1;
                foreach ($uploads as $u => $upload) {
                    if (($upload['key'] ?? '') === $key) {
                        $nth++;
                        if ($nth === $index) {
                            // Delete from S3
                            $s3Path = $upload['path'] ?? '';
                            if ($s3Path) {
                                Storage::disk('s3')->delete($s3Path);
                            }
                            unset($uploads[$u]);
                            break;
                        }
                    }
                }

                $items[$i]['uploads'] = array_values($uploads);
                $items[$i]['updated_at'] = [now()->toDateTimeString()];
                break;
            }

            $content['items'] = $items;
            $section->update(['content' => $content]);

            $this->saveStatus = 'saved';
            $this->dispatch('toast', message: 'File berhasil dihapus.', type: 'success');

        } catch (\Throwable $th) {
            info('SectionItemUpload deleteUpload failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Gagal menghapus file: ' . $th->getMessage(), type: 'error');
            $this->saveStatus = null;
        }
    }

    // ─── Private helpers ──────────────────────────────────────────────────────

    /**
     * Append a new upload entry to the item's `uploads` array and persist.
     */
    private function persistUpload(array $entry): void
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->findOrFail($this->getSectionId());

        $content = $section->content ?? [];
        $items   = $content['items'] ?? [];

        foreach ($items as $i => $item) {
            $id = $item['id'][0] ?? $item['id'] ?? null;
            if ($id !== $this->itemId) continue;

            if (!isset($items[$i]['uploads']) || !is_array($items[$i]['uploads'])) {
                $items[$i]['uploads'] = [];
            }

            $items[$i]['uploads'][]    = $entry;
            $items[$i]['updated_at']   = [now()->toDateTimeString()];
            break;
        }

        $content['items'] = $items;
        $section->update(['content' => $content]);
    }

    /**
     * Look up the section ID from the slug (cached in session / re-queried as needed).
     */
    private function getSectionId(): int|string
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($this->slug)
            ->firstOrFail();

        return $section->id;
    }

    /**
     * Resolve a human-readable file type category from MIME type and extension.
     */
    private function resolveFileType(string $mime, string $ext): string
    {
        $ext = strtolower($ext);

        if (str_starts_with($mime, 'image/') || in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'heic'])) return 'image';
        if ($mime === 'application/pdf' || $ext === 'pdf') return 'pdf';
        if (str_contains($mime, 'spreadsheet') || in_array($ext, ['xlsx', 'xls', 'csv'])) return 'spreadsheet';
        if (str_contains($mime, 'word') || in_array($ext, ['docx', 'doc'])) return 'document';
        if (str_starts_with($mime, 'video/') || in_array($ext, ['mp4', 'mov', 'avi', 'mkv', 'webm'])) return 'video';
        if (str_starts_with($mime, 'audio/') || in_array($ext, ['mp3', 'wav', 'ogg'])) return 'audio';
        
        return 'file';
    }
}

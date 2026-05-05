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

    public $availableKeys = [];
    public $newKey = '';
    public $meta = [];

    /** Toggle to show/hide the Upload Zone for this section */
    public bool $enableUpload = false;

    /** Toggle to show/hide the Social Media fields for this section */
    public bool $enableSocial = false;
    public array $activeSocialPlatforms = [];

    /** List of keys that should use EditorJS */
    public array $editorjsKeys = [];

    /** List of keys that should use Date Picker */
    public array $dateKeys = [];

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
        $this->availableKeys = $this->meta['order'] ?? [];

        // Fallback hanya jika meta order kosong dan ada items
        if (empty($this->availableKeys) && count($items) > 0) {
            $this->availableKeys = array_keys($items[0]);
        }

        // Pastikan tidak ada system keys di availableKeys
        $this->availableKeys = array_values(array_diff($this->availableKeys, $sysKeys));

        // Load section-wide toggles from meta
        $this->enableUpload = $this->meta['enable_upload'] ?? false;
        $this->enableSocial = $this->meta['enable_social'] ?? false;
        $this->activeSocialPlatforms = $this->meta['social_platforms'] ?? [];
        $this->editorjsKeys = $this->meta['editorjs_keys'] ?? [];
        $this->dateKeys = $this->meta['date_keys'] ?? [];
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-edit-key');
    }

    public function save($config = [])
    {
        if (!empty($config)) {
            $this->availableKeys = $config['keys'] ?? $this->availableKeys;
            $this->enableUpload = $config['enableUpload'] ?? $this->enableUpload;
            $this->enableSocial = $config['enableSocial'] ?? $this->enableSocial;
            $this->activeSocialPlatforms = $config['activeSocialPlatforms'] ?? $this->activeSocialPlatforms;
            $this->editorjsKeys = $config['editorjsKeys'] ?? $this->editorjsKeys;
            $this->dateKeys = $config['dateKeys'] ?? $this->dateKeys;
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

            // Jika ada items — update struktur key + backfill UUID/timestamps jika belum ada
            if (count($items) > 0) {
                $updatedItems = [];
                foreach ($items as $item) {
                    $newItem = [];

                    // Preserve system keys + backfill jika belum ada
                    foreach (['id', 'created_at', 'updated_at', 'uploads', 'attachments'] as $sysKey) {
                        if (isset($item[$sysKey])) {
                            $newItem[$sysKey] = $item[$sysKey];
                        }
                    }
                    if (!isset($newItem['id'])) {
                        $newItem['id'] = [\Illuminate\Support\Str::uuid()->toString()];
                    }
                    if (!isset($newItem['created_at'])) {
                        $newItem['created_at'] = [now()->toDateTimeString()];
                    }
                    if (!isset($newItem['updated_at'])) {
                        $newItem['updated_at'] = [now()->toDateTimeString()];
                    }

                    // Add user-defined keys (pertahankan nilai lama jika ada)
                    foreach ($this->availableKeys as $key) {
                        $newItem[$key] = $item[$key] ?? [];
                    }
                    $updatedItems[] = $newItem;
                }
                $items = $updatedItems;
            }
            // Jika items kosong — tidak perlu buat dummy, meta['order'] sudah cukup untuk menyimpan key order

            // Save key order to meta
            if (!isset($content['meta'])) {
                $content['meta'] = [];
            }
            $content['meta']['order'] = $this->availableKeys;
            $content['meta']['enable_upload'] = $this->enableUpload;
            $content['meta']['enable_social'] = $this->enableSocial;
            $content['meta']['social_platforms'] = $this->activeSocialPlatforms;
            $content['meta']['editorjs_keys'] = $this->editorjsKeys;
            $content['meta']['date_keys'] = $this->dateKeys;

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

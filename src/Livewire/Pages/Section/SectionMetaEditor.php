<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Core\Support\Cdn;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\{Computed, Debounce, Layout, Locked, Title};
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('cms::layouts.app')]
#[Title('Bale | Section Meta Editor')]
class SectionMetaEditor extends Component
{
    use WithFileUploads;

    #[Locked]
    public $sectionId;

    #[Locked]
    public $slug;

    #[Locked]
    public $usage;

    public $name = '';
    public $actived = true;

    // Mandatory meta attributes
    public $title = '';
    public $subtitle = '';
    public $buttons = [];
    public $backgroundType = 'image'; // image|slider
    public $backgroundImages = [];

    // Custom fields (dynamic based on schema)
    public $customFields = [];

    public function schemaConfig()
    {
        return config("cms.sections.{$this->usage}", []);
    }

    #[Computed]
    public function customFieldsConfig()
    {
        return $this->schemaConfig()['meta']['custom'] ?? [];
    }

    public function mount($slug)
    {
        $this->slug = $slug;

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->firstOrFail();

        $this->sectionId = $section->id;
        $this->name = $section->name;
        $this->actived = $section->actived;
        $this->usage = $section->usage ?? 'hero';

        // Load meta data
        $this->loadMetaData($section->content['meta'] ?? []);
    }

    private function loadMetaData($meta)
    {
        // Load mandatory fields
        $this->title = $meta['title'] ?? '';
        $this->subtitle = $meta['subtitle'] ?? '';
        $this->buttons = $meta['buttons'] ?? [];

        $background = $meta['background'] ?? [];
        $this->backgroundType = $background['type'] ?? 'image';
        $this->backgroundImages = $background['images'] ?? [];

        // Load custom fields from meta.custom
        $this->customFields = $meta['custom'] ?? [];

        // Initialize missing custom fields with defaults
        foreach ($this->customFieldsConfig as $key => $config) {
            if (!isset($this->customFields[$key])) {
                $this->customFields[$key] = $config['default'] ?? null;
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section-meta-editor');
    }

    // Generic save method called by Alpine
    public function save($field, $value = null)
    {
        // If value is provided (e.g. from Alpine), update the property
        if ($value !== null) {
            // Handle custom fields
            if ($field === 'custom') {
                $this->customFields = $value;
            } else {
                $this->$field = $value;
            }
        }

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->sectionId);

            $content = $section->content;
            $content['meta'] = $this->generateMetaJson();

            $section->update(['content' => $content]);

            // Dispatch event to notify Alpine of success
            $this->dispatch('field-saved', field: $field, status: 'saved');

        } catch (\Exception $e) {
            info('Save field failed: ' . $e->getMessage());
            $this->dispatch('field-saved', field: $field, status: 'error');
        }
    }

    public function updatedBackgroundImages()
    {
        $lastIndex = count($this->backgroundImages) - 1;
        $lastImage = $this->backgroundImages[$lastIndex] ?? null;

        if ($lastImage instanceof TemporaryUploadedFile) {
            try {
                $uploaded = $this->uploadBackgroundImage($lastImage);
                $this->backgroundImages[$lastIndex] = $uploaded;
                $this->save('background');
            } catch (\Exception $e) {
                info('Image upload failed: ' . $e->getMessage());
                $this->dispatch('field-saved', field: 'background', status: 'error');
                $this->dispatch('toast', message: 'Image upload failed!', type: 'error');
            }
        }
    }

    private function uploadBackgroundImage($upload)
    {
        if (!$upload) {
            return null;
        }

        $fileName = session('bale_active_slug')
            . '-' . uniqid()
            . '.' . $upload->extension();

        $path = session('bale_active_slug') . '/landing-page';

        $storedPath = $upload->storeAs(
            path: $path,
            name: $fileName,
            options: 's3'
        );

        return [
            'path' => $storedPath,
            'url' => Storage::disk('s3')->url($storedPath),
            'cdn_url' => Cdn::url('landing-page/' . $fileName),
            'disk' => 's3',
            'mime' => $upload->getMimeType(),
            'size' => $upload->getSize(),
        ];
    }

    public function removeBackgroundImage($index)
    {
        unset($this->backgroundImages[$index]);
        $this->backgroundImages = array_values($this->backgroundImages);
        $this->save('background');
    }

    private function generateMetaJson()
    {
        $meta = [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'buttons' => $this->buttons,
            'background' => [
                'type' => $this->backgroundType,
                'images' => $this->backgroundImages,
            ],
        ];

        // Add custom fields if they exist
        if (!empty($this->customFields)) {
            $meta['custom'] = $this->customFields;
        }

        return $meta;
    }

    public function toggleActive()
    {
        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->sectionId);

            $section->update(['actived' => !$this->actived]);
            $this->actived = !$this->actived;

            $this->dispatch('toast', message: 'Section status updated!', type: 'success');

        } catch (\Exception $e) {
            info('Toggle active failed: ' . $e->getMessage());
            $this->dispatch('toast', message: 'Failed to update status!', type: 'error');
        }
    }
}

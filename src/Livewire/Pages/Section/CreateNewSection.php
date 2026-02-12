<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\{Computed, Layout, Title};
use Livewire\Component;

#[Layout('cms::layouts.app')]
#[Title('Bale | Create Section')]
class CreateNewSection extends Component
{
    public $name = '';
    public $slug = '';
    public $usage = '';
    public $actived = true;

    #[Computed]
    public function schemas()
    {
        return config('cms.sections', []);
    }

    public function render()
    {
        return view('cms::livewire.pages.section.create-new-section');
    }

    public function rules()
    {
        // Pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // Ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        $schemaKeys = array_keys(config('cms.sections', []));

        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.sections', 'slug'),
            ],
            'usage' => ['required', 'string', 'in:' . implode(',', $schemaKeys)],
        ];
    }

    public function store($data)
    {
        $this->name = $data['name'] ?? $this->name;
        $this->slug = $data['slug'] ?? $this->slug;
        $this->usage = $data['usage'] ?? $this->usage;
        $this->actived = isset($data['actived']) ? (bool) $data['actived'] : $this->actived;
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan koneksi tenant aktif
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            // Get schema config
            $schemaConfig = config("cms.sections.{$this->usage}", []);

            // Initialize meta with mandatory defaults
            $mandatoryMeta = config('cms.mandatory_meta', []);
            $meta = [];

            foreach ($mandatoryMeta as $key => $config) {
                $meta[$key] = $config['default'] ?? null;
            }

            // Initialize custom fields from schema
            $customFields = $schemaConfig['meta']['custom'] ?? [];
            $customMeta = [];

            foreach ($customFields as $key => $config) {
                $customMeta[$key] = $config['default'] ?? null;
            }

            if (!empty($customMeta)) {
                $meta['custom'] = $customMeta;
            }

            // Create section dengan schema
            $section = (new Section)
                ->setConnection($connection)
                ->create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'type' => $schemaConfig['type'] ?? 'extension',
                    'usage' => $this->usage,
                    'content' => [
                        'meta' => $meta,
                        'items' => [],
                    ],
                    'actived' => $this->actived,
                ]);

            DB::commit();

            // Redirect ke meta editor
            $this->redirectRoute('bale.cms.sections.meta-editor', $this->slug, navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Section creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}

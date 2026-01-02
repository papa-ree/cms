<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\{Computed, Layout, Locked, Title};
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

#[Layout('cms::layouts.app')]
#[Title('Bale | Edit Section')]
class ExtensionSectionForm extends Component
{
    use WithFileUploads;

    #[Locked]
    public $id;
    public $name = '';
    public $slug = '';
    public $usage = 'general';
    public $content = [];
    public $actived = true;
    public $editMode = false;

    public function mount($slug = null)
    {
        if ($slug) {

            $this->slug = $slug;

            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($slug)
                ->firstOrFail();

            $this->id = $section->id;
            $this->name = $section->name;
            $this->slug = $section->slug;
            $this->usage = $section->usage;
            $this->actived = $section->actived;

            $json = $section->content['meta'] ?? [];

            $this->content = $this->convertJsonToForm($json);

            $this->editMode = true;

        } else {
            if (empty($this->content)) {
                $this->content = [
                    [
                        'key' => '',
                        'value' => '',
                        'type' => 'string',
                        'children' => []
                    ]
                ];
            }
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.extension-section-form');
    }

    /**
     * Recursive converter: JSON → Form Structure
     */
    private function convertJsonToForm($json)
    {
        $items = [];

        foreach ($json as $key => $value) {

            // Jika array, cek apakah associative atau list
            if (is_array($value)) {

                // CASE 1: associative array → nested object
                if ($this->isAssoc($value)) {

                    $items[] = [
                        'key' => $key,
                        'value' => '',
                        'type' => 'object',
                        'children' => $this->convertJsonToForm($value) // RECURSIVE
                    ];

                } else {

                    // CASE 2: list → treat sebagai children list
                    $children = [];

                    foreach ($value as $item) {
                        $children[] = [
                            'key' => '',
                            'value' => is_array($item) ? '' : (string) $item,
                            'type' => $this->detectType($item),
                            'children' => is_array($item) ? $this->convertJsonToForm($item) : []
                        ];
                    }

                    $items[] = [
                        'key' => $key,
                        'value' => '',
                        'type' => 'list',
                        'children' => $children
                    ];
                }

            } else {

                // simple value
                $items[] = [
                    'key' => $key,
                    'value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value,
                    'type' => $this->detectType($value),
                    'children' => []
                ];
            }
        }

        return $items;
    }

    /**
     * Helper cek associative array
     */
    private function isAssoc(array $arr)
    {
        return array_keys($arr) !== range(0, count($arr) - 1);
    }


    // private function convertJsonToForm($json)
    // {
    //     $items = [];

    //     foreach ($json as $key => $value) {
    //         $items[] = $this->convertNode($key, $value);
    //     }

    //     return $items;
    // }

    // private function convertNode($key, $value)
    // {
    //     // Jika nested array
    //     if (is_array($value)) {

    //         $children = [];

    //         foreach ($value as $k => $v) {
    //             $children[] = $this->convertNode($k, $v);
    //         }

    //         return [
    //             'key' => $key,
    //             'value' => '',
    //             'type' => 'string',
    //             'children' => $children
    //         ];
    //     }

    //     // Jika value biasa
    //     return [
    //         'key' => $key,
    //         'value' => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value,
    //         'type' => $this->detectType($value),
    //         'children' => []
    //     ];
    // }

    private function detectType($value)
    {
        return match (true) {
            is_array($value) && isset($value['type']) && $value['type'] === 'file' => 'file',
            is_bool($value) => 'boolean',
            is_numeric($value) => 'number',
            default => 'string',
        };
    }

    /* === Add/Remove Key Level 1 === */

    public function addKey($index = null)
    {
        if ($index) {
            $this->content[$index]['children'][] = [
                'key' => '',
                'value' => '',
                'type' => 'string',
            ];

            // reset array index agar Livewire tidak collapse field
            $this->content[$index]['children'] = array_values($this->content[$index]['children']);

        } else {
            $this->content[] = [
                'key' => '',
                'value' => '',
                'type' => 'string',
                'children' => [],
            ];
        }
    }

    public function removeKey($index)
    {
        unset($this->content[$index]);
        $this->content = array_values($this->content);
    }

    /* === Subkeys === */

    public function addSubKey(...$path)
    {
        $this->addSubKeyAtPath($this->content[$path[0]], array_slice($path, 1));
    }

    public function removeSubKey(...$path)
    {
        $this->removeSubKeyAtPath($this->content[$path[0]], array_slice($path, 1));
    }

    private function addSubKeyAtPath(&$node, $path)
    {
        if (empty($path)) {
            $node['children'][] = [
                'key' => '',
                'value' => '',
                'type' => 'string',
                'children' => []
            ];
            return;
        }

        $next = array_shift($path);
        $this->addSubKeyAtPath($node['children'][$next], $path);
    }

    private function removeSubKeyAtPath(&$node, $path)
    {
        if (count($path) === 1) {
            unset($node['children'][$path[0]]);
            $node['children'] = array_values($node['children']);
            return;
        }

        $next = array_shift($path);
        $this->removeSubKeyAtPath($node['children'][$next], $path);
    }

    public function addSubSubKey($i, $j)
    {
        if (!isset($this->content[$i]['children'][$j]['children'])) {
            $this->content[$i]['children'][$j]['children'] = [];
        }

        $this->content[$i]['children'][$j]['children'][] = [
            'key' => '',
            'value' => '',
            'type' => 'string',
            'children' => []
        ];

        $this->content[$i]['children'][$j]['children'] =
            array_values($this->content[$i]['children'][$j]['children']);
    }

    public function removeSubSubKey($i, $j, $k)
    {
        unset($this->content[$i]['children'][$j]['children'][$k]);

        $this->content[$i]['children'][$j]['children'] =
            array_values($this->content[$i]['children'][$j]['children']);
    }

    /* Pastikan setiap children adalah array */
    private function ensureChildrenStructure()
    {
        foreach ($this->content as &$item) {
            if (!isset($item['children']) || !is_array($item['children'])) {
                $item['children'] = [];
            }

            foreach ($item['children'] as &$child) {
                if (!isset($child['children']) || !is_array($child['children'])) {
                    $child['children'] = [];
                }
            }
        }
    }

    public function updatedContent()
    {
        $this->ensureChildrenStructure();
    }

    /* === Generate Output JSON === */

    // private function castValue($value, $type)
    // {
    //     return match ($type) {
    //         'boolean' => filter_var($value, FILTER_VALIDATE_BOOL),
    //         'number' => is_numeric($value) ? ($value + 0) : $value,
    //         default => $value
    //     };
    // }

    private function castValue($value, $type)
    {
        if ($type === 'file') {

            // Jika sudah file metadata (update tanpa ganti file)
            if (is_array($value) && isset($value['path'])) {
                return $value;
            }

            // Jika upload baru
            if ($value instanceof TemporaryUploadedFile) {
                return $this->uploadToMinio($value);
            }

            return null;
        }

        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOL),
            'number' => is_numeric($value) ? ($value + 0) : $value,
            default => $value
        };
    }

    //upload handler
    private function uploadToMinio($upload)
    {
        if (!$upload)
            return null;

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
            'type' => 'file',
            'disk' => 's3',
            'path' => $storedPath,
            'url' => Storage::disk('s3')->url($storedPath),
            'mime' => $upload->getMimeType(),
            'size' => $upload->getSize(),
        ];
    }

    private function generateMetaJson()
    {
        $out = [];

        foreach ($this->content as $node) {
            if (!$node['key'])
                continue;
            $out[$node['key']] = $this->convertNodeToJson($node);
        }

        return $out;
    }

    // private function convertNodeToJson($node)
    // {
    //     // Nested
    //     if (!empty($node['children'])) {
    //         $arr = [];
    //         foreach ($node['children'] as $child) {
    //             if (!$child['key'])
    //                 continue;
    //             $arr[$child['key']] = $this->convertNodeToJson($child);
    //         }
    //         return $arr;
    //     }

    //     // Simple value
    //     return $this->castValue($node['value'], $node['type']);
    // }

    private function convertNodeToJson($node)
    {
        // Nested
        if (!empty($node['children'])) {
            $arr = [];
            foreach ($node['children'] as $child) {
                if (!$child['key'])
                    continue;
                $arr[$child['key']] = $this->convertNodeToJson($child);
            }
            return $arr;
        }

        // FILE TYPE
        if ($node['type'] === 'file') {
            return $this->castValue($node['value'], 'file');
        }

        // Simple value
        return $this->castValue($node['value'], $node['type']);
    }


    public function rules()
    {
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        return [
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.sections', 'slug')->ignore($this->id),
            ],
        ];
    }

    public function save($slug)
    {
        // dd($this);
        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $content = [
                "meta" => $this->generateMetaJson(),
                "items" => [],
            ];

            (new Section)
                ->setConnection($connection)
                ->create([
                    'name' => $this->name,
                    'slug' => $this->slug,
                    'usage' => $this->usage,
                    'type' => 'extension',
                    'content' => $content,
                    'actived' => $this->actived,
                ]);

            $this->dispatch('toast', message: 'New Section Saved!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Section update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }

    }

    public function update($slug)
    {
        // dd($this);

        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->id);

            $existing = $section->content;
            $meta = $this->generateMetaJson();

            $content = [
                'meta' => $meta,
                'items' => $existing['items'] ?? []
            ];

            $section->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'content' => $content,
                'actived' => $this->actived,
            ]);
            DB::commit();

            $this->dispatch('toast', message: 'New Section Saved!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Section update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }

    public function toggle()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($this->slug)
            ->firstOrFail();

        // $content = $section->content;

        // $content['is_active'] = !$content['is_active'];

        $section->update([
            'actived' => !$this->actived
        ]);
    }
}
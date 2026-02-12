<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Livewire\WithFileUploads;

#[Layout('cms::layouts.app')]
#[Title('Bale | Edit Searchable Section')]
class SearchableSectionForm extends Component
{
    use WithFileUploads;

    #[Locked]
    public $id;
    public $name = '';
    public $slug = '';
    public $availableKeys = [];          // daftar key yg dapat digunakan
    public $newKey = '';                 // input key baru
    public $meta = [];
    public $items = [];                  // items[i][key] = ['value1', 'value2', ...]
    public $tempInputs = [];             // temporary input untuk add value: [itemIndex][key] = 'newValue'
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

            $content = $section->content ?? [];
            $this->meta = $content['meta'] ?? [];   // tidak boleh diubah
            $this->items = $content['items'] ?? [];   // hanya ini yg boleh diedit

            // Generate keys berdasarkan item pertama
            if (count($this->items) > 0) {
                $this->availableKeys = array_keys($this->items[0]);

                // Migrate old data structure (string) to new structure (array) for backward compatibility
                foreach ($this->items as &$item) {
                    foreach ($item as $key => &$value) {
                        // Convert string values to array
                        if (!is_array($value)) {
                            $value = $value !== '' ? [$value] : [];
                        }
                    }
                }
            }

            $this->editMode = true;
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.searchable-section-form');
    }

    public function addKey()
    {
        if (!$this->newKey || in_array($this->newKey, $this->availableKeys))
            return;

        $this->availableKeys[] = $this->newKey;

        // tambahkan key ke semua item dengan empty array
        foreach ($this->items as &$item) {
            $item[$this->newKey] = [];
        }

        $this->newKey = '';
    }

    public function removeKey($index)
    {
        $key = $this->availableKeys[$index];

        unset($this->availableKeys[$index]);
        $this->availableKeys = array_values($this->availableKeys);

        // hapus key dari items saja
        foreach ($this->items as &$item) {
            unset($item[$key]);
        }
    }

    public function addItem()
    {
        $item = [];

        // Initialize setiap key dengan empty array
        foreach ($this->availableKeys as $key) {
            $item[$key] = [];
        }

        $this->items[] = $item;
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addValue($itemIndex, $key)
    {
        // Validasi input tidak kosong
        if (!isset($this->tempInputs[$itemIndex][$key]) || $this->tempInputs[$itemIndex][$key] === '') {
            return;
        }

        // Ensure items array structure exists
        if (!isset($this->items[$itemIndex][$key])) {
            $this->items[$itemIndex][$key] = [];
        }

        // Convert to array if it's still a string (backward compatibility)
        if (!is_array($this->items[$itemIndex][$key])) {
            $this->items[$itemIndex][$key] = $this->items[$itemIndex][$key] !== ''
                ? [$this->items[$itemIndex][$key]]
                : [];
        }

        // Add new value
        $this->items[$itemIndex][$key][] = $this->tempInputs[$itemIndex][$key];

        // Clear temporary input
        $this->tempInputs[$itemIndex][$key] = '';
    }

    public function removeValue($itemIndex, $key, $valueIndex)
    {
        // Ensure it's an array
        if (!is_array($this->items[$itemIndex][$key])) {
            return;
        }

        // Remove value
        unset($this->items[$itemIndex][$key][$valueIndex]);

        // Re-index array
        $this->items[$itemIndex][$key] = array_values($this->items[$itemIndex][$key]);
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
        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $data = [
                'name' => $this->name,
                'slug' => $this->slug,
                'type' => 'extension',
                'usage' => 'searchable',
                'content' => [
                    'meta' => $this->meta ?? [],
                    'items' => $this->items,
                ],
            ];

            (new Section)
                ->setConnection($connection)
                ->create($data);

            DB::commit();

            // session()->flash('success', 'Section Updated!');

            // $this->redirectRoute('bale.cms.sections.index', navigate: true);
            $this->dispatch('toast', message: 'Item Saved!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Section update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }

    public function update($slug)
    {
        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {

            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->findOrFail($this->id);

            // Ambil meta lama tanpa diubah
            $oldContent = $section->content ?? [];

            $content = [
                'meta' => $oldContent['meta'] ?? [],   // tidak berubah
                'items' => $this->items,                // hanya items yg berubah
            ];

            $section->update([
                'name' => $this->name,
                'slug' => $this->slug,
                'content' => $content,
            ]);

            DB::commit();
            $this->dispatch('toast', message: 'Item Updated!', type: 'success');

            // session()->flash('success', 'Section Updated!');

            // $this->redirectRoute('bale.cms.sections.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Section update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}
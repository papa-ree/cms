<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked};
use Livewire\WithFileUploads;

#[Layout('cms::layouts.app')]
class UploadImage extends Component
{
    use WithFileUploads;

    #[Locked]
    public string $slug;
    public $section = [];

    public $backgrounds = [];

    public function mount($slug)
    {
        $this->slug = $slug;

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->firstOrFail();

        $this->section = $section->content ?? [];
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.upload-image');
    }


    public function uploadImages()
    {
        DB::beginTransaction();
        try {

            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($this->slug)
                ->firstOrFail();

            $content = $section->content;

            foreach ($this->backgrounds as $upload) {
                $file_name = session('bale_active_slug') . '-' . uniqid() . '.' . $upload->extension();

                $upload->storeAs(
                    path: session('bale_active_slug') . '/landing-page',
                    name: $file_name,
                    options: 's3'
                );

                $content['backgrounds'][] = [
                    "alt" => pathinfo($file_name, PATHINFO_FILENAME),
                    "path" => $file_name,
                    "type" => "image",
                    "caption" => "Background " . (count($content['backgrounds']) + 1),
                    "position" => "center",
                ];
            }

            $section->update([
                'content' => $content
            ]);

            $this->backgrounds = [];
            DB::commit();

            $this->dispatch('toast', message: 'Image Uploaded!', type: 'success');
            $this->redirectRoute('bale.cms.sections.edit', $this->slug, navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            Log::info('Upload Hero image failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }

    public function deleteImage($path)
    {
        DB::beginTransaction();
        try {
            // ds($path);
            $slug = session('bale_active_slug');

            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($this->slug)
                ->firstOrFail();

            // Ambil content
            $content = $section->content ?? [];

            // Pastikan key backgrounds ada
            if (!isset($content['backgrounds']) || !is_array($content['backgrounds'])) {
                return; // aman keluar
            }

            // Hapus file di S3
            $filePath = $slug . '/landing-page/' . $path;

            if (Storage::disk('s3')->exists($filePath)) {
                Storage::disk('s3')->delete($filePath);
            }

            // Filter backgrounds berdasarkan path
            $content['backgrounds'] = array_values(
                array_filter($content['backgrounds'], function ($item) use ($path) {
                    return ($item['path'] ?? null) !== $path;
                })
            );

            // Simpan kembali
            $section->update([
                'content' => $content
            ]);

            // Sinkronkan ke Livewire
            $this->section = $content;
            $this->dispatch('toast', message: 'Image Deleted!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}
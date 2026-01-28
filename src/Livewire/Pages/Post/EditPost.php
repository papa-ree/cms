<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Livewire\WithFileUploads;
use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;

#[Layout('cms::layouts.post-editor')]
#[Title('Bale | Edit Post')]
class EditPost extends Component
{
    use WithFileUploads;

    #[Locked]
    public $id;
    public $post;
    public $title;
    public $content;
    public $thumbnail;
    public $thumbnail_new;
    public $slug;
    public $updated_at;
    public $published;
    public $publish_at;
    public $tag;
    public $availableTag;
    public $category_id;
    public $category_name;
    public $category_slug;
    public $show_upload_zone = true;

    public function mount($slug)
    {
        // Pastikan koneksi tenant aktif SEBELUM query dilakukan
        TenantConnectionService::ensureActive();

        // Ambil data dari tenant database, bukan landlord
        $post = Post::whereSlug($slug)->firstOrFail();

        if (!is_null($post)) {
            $this->id = $post->id;
            $this->title = $post->title;
            $this->slug = $post->slug;
            $this->updated_at = $post->updated_at;
            $this->content = json_decode(json_encode($post->content), true);
            $this->thumbnail = $post->thumbnail;
            $this->published = $post->published;
            $this->show_upload_zone = $post->thumbnail ? false : true;
            $this->category_name = $post->category_slug ?? null;
            $this->category_slug = $post->category_slug ?? null;
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.post.edit-post');
    }

    public function rules()
    {
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        return [
            'title' => 'required|string|min:3|max:50',
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.posts', 'slug')->ignore($this->id),
            ],
        ];
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $dataToUpdate = [
                'title' => $this->title,
                'slug' => $this->slug,
                'content' => $this->content,
            ];

            // Thumbnail is now handled immediately via updatedThumbnailNew()

            // Single atomic update using ID
            (new Post)
                ->setConnection($connection)
                ->find($this->id)
                ->update($dataToUpdate);

            DB::commit();

            // Dispatch events for UI feedback
            $this->dispatch('toast', message: 'Post berhasil disimpan!', type: 'success');
            $this->dispatch('save-complete');

            // session()->flash('success', 'Post Updated!');

            $this->redirectRoute('bale.cms.posts.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();

            // Dispatch failure events
            $this->dispatch('save-complete');
            $this->dispatch('toast', message: 'Gagal menyimpan post: ' . $th->getMessage(), type: 'error');

            info('Post update failed: ' . $th->getMessage());
        }
    }

    private function uploadThumbnail()
    {
        if ($this->thumbnail_new) {
            // set name by slug
            $thumbnail_name = session('bale_active_slug') . '-' . uniqid() . '.' . $this->thumbnail_new->extension();

            // Define final path in S3
            $path = session('bale_active_slug') . '/thumbnails';

            // Use storeAs which is more reliable for Livewire files
            $storedPath = $this->thumbnail_new->storeAs(
                path: $path,
                name: $thumbnail_name,
                options: 's3'
            );

            if ($storedPath) {
                return $thumbnail_name;
            }
        }

        return null;
    }

    public function deleteThumbnail()
    {
        Storage::disk('s3')->delete(session('bale_active_slug') . '/thumbnails/' . $this->thumbnail);

        TenantConnectionService::ensureActive();

        $post = Post::where('slug', $this->slug)->firstOrFail();
        $post->update(['thumbnail' => null]);
        $this->thumbnail = null;
        $this->show_upload_zone = true;
    }

    public function updated($propertyName)
    {
        // Auto-save when content changes
        if ($propertyName === 'content') {
            $this->autoSave();
        }
    }

    public function updatedThumbnailNew()
    {
        // Skip manual validation here if it causes "Unable to retrieve file_size" on S3 temp disk
        // Filepond and Livewire's own config handles basic limits

        try {
            $thumbnail_name = $this->uploadThumbnail();

            if ($thumbnail_name) {
                TenantConnectionService::ensureActive();
                $connection = TenantConnectionService::connection();

                // Update database immediately
                (new Post)
                    ->setConnection($connection)
                    ->find($this->id)
                    ->update(['thumbnail' => $thumbnail_name]);

                // Update local state
                $this->thumbnail = $thumbnail_name;
                $this->show_upload_zone = false;
                $this->thumbnail_new = null;

                $this->dispatch('toast', message: 'Thumbnail updated immediately!', type: 'success');
            }
        } catch (\Throwable $th) {
            $this->dispatch('toast', message: 'Failed to upload thumbnail: ' . $th->getMessage(), type: 'error');
            info('Immediate thumbnail upload failed: ' . $th->getMessage());
        }
    }

    public function autoSave()
    {
        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $post = (new Post)
                ->setConnection($connection)
                ->find($this->id);

            if ($post) {
                $post->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

                $this->dispatch('toast', message: 'Auto-saved successfully!', type: 'success');
            }
        } catch (\Throwable $th) {
            info('Auto-save failed: ' . $th->getMessage());
        }
    }
}
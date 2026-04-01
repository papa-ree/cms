<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Livewire\WithFileUploads;
use Bale\Cms\Models\Post;
use Bale\Cms\Models\Category;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;

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
    public $saveStatus = 'editing'; // editing, saving, saved, error

    public function mount($slug)
    {
        $this->authorize('bale-post.read');
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

    #[Computed]
    public function categories()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();
        return (new Category)
            ->setConnection($connection)
            ->orderBy('name', 'asc')
            ->get();
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
            'category_slug' => 'nullable|string',
        ];
    }

    public function update()
    {
        // Manual save trigger (if still called by some shortcut)
        $this->autoSave();
    }

    private function uploadThumbnail()
    {
        if ($this->thumbnail_new) {
            // set name by slug, use getClientOriginalExtension for reliability
            $extension = $this->thumbnail_new->getClientOriginalExtension();
            $thumbnail_name = session('bale_active_slug') . '-' . uniqid() . '.' . $extension;

            // Define final path in S3
            $finalPath = session('bale_active_slug') . '/thumbnails/' . $thumbnail_name;

            // Upload to S3 using Storage facade with get() to read contents from temp
            Storage::disk('s3')->put($finalPath, $this->thumbnail_new->get());

            return $thumbnail_name;
        }

        return null;
    }

    public function deleteThumbnail()
    {
        $this->saveStatus = 'saving';

        if ($this->thumbnail) {
            Storage::disk('s3')->delete(session('bale_active_slug') . '/thumbnails/' . $this->thumbnail);
        }

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $post = Post::on($connection)->where('slug', $this->slug)->firstOrFail();
        $post->update(['thumbnail' => null]);
        $this->thumbnail = null;
        $this->show_upload_zone = true;

        $this->saveStatus = 'saved';
        $this->dispatch('status-updated', status: 'saved');

        // Reset to editing status after 2 seconds
        $this->dispatch('post-status-reset');
    }

    public function updated($propertyName)
    {
        // Fields that trigger auto-save
        $autoSaveFields = ['title', 'slug', 'content', 'category_slug'];

        if (in_array($propertyName, $autoSaveFields)) {
            $this->autoSave();
        }
    }

    public function updatedThumbnailNew()
    {
        // Skip manual validation here if it causes "Unable to retrieve file_size" on S3 temp disk
        // Filepond and Livewire's own config handles basic limits

        try {
            // Delete old thumbnail if it exists
            if ($this->thumbnail) {
                Storage::disk('s3')->delete(session('bale_active_slug') . '/thumbnails/' . $this->thumbnail);
            }

            $thumbnail_name = $this->uploadThumbnail();

            if ($thumbnail_name) {
                TenantConnectionService::ensureActive();
                $connection = TenantConnectionService::connection();

                // Update database immediately
                Post::on($connection)
                    ->find($this->id)
                    ->update(['thumbnail' => $thumbnail_name]);

                // Update local state
                $this->thumbnail = $thumbnail_name;
                $this->show_upload_zone = false;
                $this->thumbnail_new = null;

                $this->saveStatus = 'saved';
                $this->dispatch('status-updated', status: 'saved');
            }
        } catch (\Throwable $th) {
            $this->saveStatus = 'error';
            $this->dispatch('status-updated', status: 'error');
            info('Immediate thumbnail upload failed: ' . $th->getMessage());
        }
    }

    public function autoSave()
    {
        $this->saveStatus = 'saving';
        $this->dispatch('status-updated', status: 'saving');

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $post = Post::on($connection)
                ->find($this->id);

            if ($post) {
                $post->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                    'category_slug' => $this->category_slug,
                ]);

                $this->saveStatus = 'saved';
                $this->dispatch('status-updated', status: 'saved');
            }
        } catch (\Throwable $th) {
            $this->saveStatus = 'error';
            $this->dispatch('status-updated', status: 'error');
            info('Auto-save failed: ' . $th->getMessage());
        }
    }
}

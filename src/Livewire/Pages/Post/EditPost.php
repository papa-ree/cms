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

    public function update($slug)
    {
        $this->slug = $slug['slug'];

        $this->validate();

        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', saved: true);

            $post = Post::where('slug', $this->slug)->firstOrFail();

            if ($this->thumbnail_new) {
                $this->uploadThumbnail();
            }

            $post = (new Post)
                ->setConnection($connection)
                ->find($this->id)
                ->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

            DB::commit();
            session()->flash('success', 'Post Updated!');
            // $this->dispatch('toast', message: 'Data berhasil disimpan!', type: 'success');

            $this->redirectRoute('bale.cms.posts.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            // $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    private function uploadThumbnail()
    {
        if ($this->thumbnail_new != null or $this->thumbnail_new != "") {

            // set name by slug
            $thumbnail_name = $this->slug . '-' . uniqid() . '.' . $this->thumbnail_new->extension();

            // store image
            $this->thumbnail_new->storeAs(path: session('bale_active_slug') . '/thumbnails', name: $thumbnail_name, options: 's3');

            TenantConnectionService::ensureActive();

            // update image name in post table
            $post = Post::where('slug', $this->slug)->firstOrFail();

            $post->update(['thumbnail' => $thumbnail_name]);
        }
    }

    public function deleteThumbnail()
    {
        Storage::delete(session('bale_active_slug') . '//thumbnails/' . $this->thumbnail);

        TenantConnectionService::ensureActive();

        $post = Post::where('slug', $this->slug)->firstOrFail();
        $post->update(['thumbnail' => null]);
        $this->thumbnail = null;
        $this->show_upload_zone = true;
    }
}
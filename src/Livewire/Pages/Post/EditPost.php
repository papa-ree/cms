<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Livewire\WithFileUploads;
use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.post-editor')]
#[Title('Edit Post')]
class EditPost extends Component
{
    use WithFileUploads;

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
        $post = Post::where('slug', $slug)->firstOrFail();

        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = json_decode(json_encode($post->content), true);
        $this->thumbnail = $post->thumbnail;
        $this->published = $post->published;
        $this->show_upload_zone = $post->thumbnail ? false : true;
        $this->category_name = $post->category_slug ?? null;
        $this->category_slug = $post->category_slug ?? null;

        // dd(($post));
    }

    public function render()
    {
        return view('cms::livewire.pages.post.edit-post');
    }

    public function update(LivewireAlert $alert)
    {
        // $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan koneksi tenant aktif
            TenantConnectionService::ensureActive();

            $this->dispatch('disabling-button', params: true);

            // $this->post->setConnection(TenantManager::getActiveConnection());
            $post = Post::where('slug', $this->slug)->firstOrFail();

            if ($this->thumbnail_new) {
                $this->uploadThumbnail();
            }

            $slug = session('bale_active_slug');
            $content = json_encode($this->content); // misalnya hasil dari EditorJS

            // Temukan semua file lokal sementara yang dipakai di konten
            preg_match_all('/\/cms\/media\/(private\/livewire-tmp\/[^\s"\']+)/', $content, $matches);
            $tmpFiles = $matches[1] ?? [];

            foreach ($tmpFiles as $tmpPath) {
                if (Storage::disk('local')->exists($tmpPath)) {
                    $fileName = basename($tmpPath);
                    $newPath = "{$slug}/editorjs/{$fileName}";

                    // ambil file dari local storage
                    $file = Storage::disk('local')->get($tmpPath);

                    // simpan ke MinIO di direktori sesuai slug bale
                    Storage::disk('minio')->put($newPath, $file);

                    // hapus file lokal
                    Storage::disk('local')->delete($tmpPath);

                    // ubah URL di konten agar pakai path baru
                    $content = str_replace(
                        url('/cms/media/' . $tmpPath),
                        url('/cms/media/' . $newPath),
                        $content
                    );
                }
            }

            $post->update([
                'content' => json_decode(json_encode($this->content), true),
            ]);

            DB::commit();

            session()->flash('saved', [
                'title' => 'Post Updated!',
            ]);

            $this->redirectRoute('bale.cms.posts.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

    private function uploadThumbnail()
    {
        if ($this->thumbnail_new != null or $this->thumbnail_new != "") {

            // set name by slug
            $thumbnail_name = $this->slug . '-' . uniqid() . '.' . $this->thumbnail_new->extension();

            // store image
            $this->thumbnail_new->storeAs(path: session('bale_active_slug') . '/thumbnails', name: $thumbnail_name, options: 'minio');

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
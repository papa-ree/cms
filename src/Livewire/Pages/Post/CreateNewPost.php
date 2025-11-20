<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use Livewire\Attributes\{Layout, Validate};
use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.app')]
class CreateNewPost extends Component
{

    #[Validate('required|string|min:5')]
    public $title;

    // #[Validate('required|unique:posts,slug|string|min:5')]
    // public $slug;

    public $tag;

    public function render()
    {
        return view('cms::livewire.pages.post.create-new-post');
    }

    public function store(LivewireAlert $alert)
    {
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan koneksi tenant aktif
            TenantConnectionService::ensureActive();

            $this->dispatch('disabling-button', params: true);

            $post = Post::create([
                'author' => Auth::user()->uuid,
                'title' => $this->title,
                'slug' => Str::slug($this->title),
                'published' => false,
                'publish_at' => now(),
            ]);

            DB::commit();

            session()->flash('saved', [
                'title' => 'New Post Created!',
            ]);

            $this->redirectRoute('bale.cms.posts.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();
        }
    }

}
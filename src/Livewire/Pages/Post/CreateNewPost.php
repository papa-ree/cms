<?php

namespace Bale\Cms\Livewire\Pages\Post;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Attributes\{Layout, Validate};
use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;

#[Layout('cms::layouts.app')]
class CreateNewPost extends Component
{

    public $title;
    public $slug;

    public $tag;

    public function render()
    {
        return view('cms::livewire.pages.post.create-new-post');
    }

    public function rules()
    {
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
        $connection = TenantConnectionService::connection();

        return [
            'title' => ['required', 'string', 'min:3', 'max:50'],
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.posts', 'slug'),
            ],
        ];
    }

    public function store($slug)
    {
        $this->slug = $slug['slug'];
        $this->validate();

        DB::beginTransaction();

        try {
            // Pastikan koneksi tenant aktif
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', params: true);

            $post = (new Post)
                ->setConnection($connection)
                ->create([
                    'author' => Auth::user()->uuid,
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'published' => false,
                ]);

            DB::commit();

            session()->flash('success', 'New Post Created!');

            $this->redirectRoute('bale.cms.posts.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }

}
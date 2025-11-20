<?php

namespace Bale\Cms\Livewire\Pages\Post\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout, On, Computed};
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Bale\Cms\Models\Post;

#[Layout('cms::layouts.app')]
class PostTable extends Component
{
    use WithPagination, WithoutUrlPagination;

    public $query = '';

    #[On('refresh-post')]
    public function render()
    {
        return view('cms::livewire.pages.post.section.post-table');
    }

    public function updating($key): void
    {
        if ($key === 'query') {
            $this->resetPage();
        }
    }

    public function updatedPage()
    {
        $this->dispatch('paginated');
    }

    #[Computed]
    public function availablePosts()
    {
        return Post::where('title', 'like', '%' . $this->query . '%')->orderByDesc('created_at')->paginate(10);
    }

    public function publishPost(Post $post)
    {
        $post->update(['published' => true, 'published_at' => now()]);

        // activity()
        // ->causedBy(auth()->user())
        // ->performedOn($post)
        // ->useLog('post')
        // ->event('updated')
        // ->log('The user has published the post.');
    }

    public function unpublishPost(Post $post)
    {
        $post->update(['published' => false, 'published_at' => null]);

        // activity()
        // ->causedBy(auth()->user())
        // ->performedOn($post)
        // ->useLog('post')
        // ->event('updated')
        // ->log('The user has unpublished the post.');
    }
}
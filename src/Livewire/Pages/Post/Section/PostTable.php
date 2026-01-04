<?php

namespace Bale\Cms\Livewire\Pages\Post\Section;

use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;
use Livewire\Component;
use Livewire\Attributes\{Layout, On, Computed};
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Bale\Cms\Models\Post;

#[Layout('cms::layouts.app')]
class PostTable extends Component
{
    use WithPagination, WithoutUrlPagination, HasSafeDelete;
    protected string $modelClass = Post::class;


    public $query = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    public $filterPublished = '';

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function resetFilter($field)
    {
        if ($field === 'published')
            $this->reset('filterPublished');
    }
    public function resetAllFilters()
    {
        $this->reset(['filterPublished', 'query']);
    }

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
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();
        return (new Post)
            ->setConnection($connection)
            ->where('title', 'like', '%' . $this->query . '%')
            ->when($this->filterPublished, function ($query) {
                if ($this->filterPublished === 'published') {
                    $query->where('published', true);
                } elseif ($this->filterPublished === 'unpublished') {
                    $query->where('published', false);
                }
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function publishPost($post)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        (new Post)
            ->setConnection($connection)
            ->find($post)
            ->update(['published' => true, 'published_at' => now()]);

        // activity()
        // ->causedBy(auth()->user())
        // ->performedOn($post)
        // ->useLog('post')
        // ->event('updated')
        // ->log('The user has published the post.');
    }

    public function unpublishPost($post)
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        (new Post)
            ->setConnection($connection)
            ->find($post)
            ->update(['published' => false, 'published_at' => null]);

        // activity()
        // ->causedBy(auth()->user())
        // ->performedOn($post)
        // ->useLog('post')
        // ->event('updated')
        // ->log('The user has unpublished the post.');
    }
}
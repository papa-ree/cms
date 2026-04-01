<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Bale\Cms\Models\Post;
use Bale\Cms\Services\TenantConnectionService;
use Livewire\Attributes\Locked;
use Livewire\Component;

class PostStatusToggle extends Component
{
    #[Locked]
    public int|string $postId;

    public bool $published;

    public function mount(int|string $postId, bool $published): void
    {
        $this->postId    = $postId;
        $this->published = $published;
    }

    public function toggle(): void
    {
        TenantConnectionService::ensureActive();

        $this->published = ! $this->published;

        Post::on(TenantConnectionService::connection())
            ->where('id', $this->postId)
            ->update([
                'published'    => $this->published,
                'published_at' => $this->published ? now() : null,
            ]);

        $message = $this->published
            ? __('Post published successfully!')
            : __('Post unpublished.');

        $this->dispatch('toast', message: $message, type: 'success');
    }

    public function render()
    {
        return view('cms::livewire.shared-components.post-status-toggle');
    }
}

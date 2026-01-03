<?php

namespace Bale\Cms\Livewire\Pages\Overview;

use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Attributes\Layout;

class Index extends Component
{
    #[Layout('cms::layouts.app')]
    #[Title('Bale | Overview')]
    #[Lazy]
    public function render()
    {
        $analytics = new \Bale\Cms\Services\AnalyticsService();

        return view('cms::livewire.pages.overview.index', [
            'internalStats' => $analytics->getInternalStats(),
            'externalStats' => $analytics->getExternalStats(),
            'recentPosts' => $analytics->getRecentPosts(),
        ]);
    }
}
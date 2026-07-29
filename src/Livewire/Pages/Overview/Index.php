<?php

namespace Bale\Cms\Livewire\Pages\Overview;

use Bale\Cms\Services\AnalyticsService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\Title;
use Livewire\Component;

class Index extends Component
{
    #[Layout('cms::layouts.app')]
    #[Title('Bale | Overview')]
    #[Lazy]
    public function render()
    {
        $analytics = new AnalyticsService;

        return view('cms::livewire.pages.overview.index', [
            'internalStats' => $analytics->getInternalStats(),
            'externalStats' => $analytics->getExternalStats(),
            'recentPosts' => $analytics->getRecentPosts(),
        ]);
    }
}

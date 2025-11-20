<?php

namespace Bale\Cms\Livewire\SharedComponents;

use Livewire\Component;
use Livewire\Attributes\{Computed, Layout};

class PrabanSidebar extends Component
{
    #[Layout('cms::layouts.app')]
    public function render()
    {
        return view('cms::livewire.shared-components.praban-sidebar');
    }

    #[Computed]
    public function availableMenus()
    {
        $menu = [
            ['label' => 'overview', 'url' => 'overview'],
            ['label' => 'posts', 'url' => 'posts'],
            ['label' => 'pages', 'url' => 'pages'],
            ['label' => 'navigations', 'url' => 'navigations'],
        ];

        return $menu;
    }
}
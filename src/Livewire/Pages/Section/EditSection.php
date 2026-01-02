<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};

#[Layout('cms::layouts.app')]
#[Title('Bale | Edit Section')]
class EditSection extends Component
{
    public $slug;

    public function mount($slug)
    {
        $this->slug = $slug;
    }

    public function render()
    {
        return view('cms::livewire.pages.section.edit-section');
    }

}
<?php

namespace Bale\Cms\Livewire\Pages\Section;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

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

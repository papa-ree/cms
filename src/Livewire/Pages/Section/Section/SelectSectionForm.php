<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Livewire\Component;
use Livewire\Attributes\{Layout};

#[Layout('cms::layouts.app')]
class SelectSectionForm extends Component
{
    public function render()
    {
        return view('cms::livewire.pages.section.section.select-section-form');
    }
}
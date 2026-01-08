<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Option;
use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked};

#[Layout('cms::layouts.app')]
class HeroSectionForm extends Component
{
    public $section = [];

    #[Locked]
    public string $slug;

    public string $url;

    public function mount($slug)
    {
        $this->slug = $slug;

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($slug)
            ->first();

        $this->section = $section->content ?? [];
        $this->url = Option::whereName('url')->first()->value ?? [];
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.hero-section-form');
    }

    public function rules()
    {
        return [
            'section.title' => ['required', 'string', 'min:4', 'max:100'],
            'section.subtitle' => ['required', 'string', 'min:4', 'max:255'],
            'section.organization' => ['required', 'string', 'min:4', 'max:255'],
            'section.buttons.*.label' => ['required', 'string', 'min:4', 'max:30'],
            'section.buttons.*.url' => ['required', 'string', 'min:4', 'max:30'],
        ];
    }

    public function update()
    {
        $this->validate();

        DB::beginTransaction();
        try {

            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $section = (new Section)
                ->setConnection($connection)
                ->whereSlug($this->slug)
                ->firstOrFail();

            $section->update([
                'content' => $this->section
            ]);

            DB::commit();

            $this->dispatch('toast', message: 'Section Updated!', type: 'success');

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Post creation failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }
}
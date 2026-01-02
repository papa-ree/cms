<?php

namespace Bale\Cms\Livewire\Pages\Section\Section;

use Bale\Cms\Models\Option;
use Bale\Cms\Models\Section;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked};

#[Layout('cms::layouts.app')]
class PostSectionForm extends Component
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
            ->firstOrFail();

        $this->section = $section->content ?? [];
        $this->url = Option::whereName('url')->firstOrFail()->value;
    }

    public function render()
    {
        return view('cms::livewire.pages.section.section.post-section-form');
    }

    public function rules()
    {
        return [
            'section.title' => ['required', 'string', 'min:4', 'max:50'],
            'section.subtitle' => ['required', 'string', 'min:4', 'max:255'],
            'section.layouts.grid' => ['required', 'integer:strict', 'min:2', 'max:4'],
            'section.layouts.post_limit' => ['required', 'integer:strict', 'min:2', 'max:6'],
            'section.buttons.*.label' => ['required', 'string', 'min:4', 'max:30'],
            'section.buttons.*.url' => ['required', 'string', 'min:4', 'max:30'],
        ];
    }

    public function update()
    {
        // dump(intval($this->section['layouts']['grid']));
        $this->section['layouts']['grid'] = intval($this->section['layouts']['grid']);
        $this->section['layouts']['post_limit'] = intval($this->section['layouts']['post_limit']);
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
            info('Post Section Update failed: ' . $th->getMessage());
            $this->dispatch('toast', message: 'Something Wrong!', type: 'error');
        }
    }

    public function toggle()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $section = (new Section)
            ->setConnection($connection)
            ->whereSlug($this->slug)
            ->firstOrFail();

        $content = $section->content;

        $content['is_active'] = !$content['is_active'];

        $section->update([
            'content' => $content
        ]);
    }
}
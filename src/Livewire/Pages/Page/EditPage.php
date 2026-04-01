<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Bale\Cms\Models\Page;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.page-editor')]
#[Title('Bale | ' . 'Edit Page')]
class EditPage extends Component
{
    #[Locked]
    public $id;
    public $title;
    public $slug;
    public $content;
    public $updated_at;
    public $locked;
    public $show_setting = false;
    public $saveStatus = 'editing'; // editing, saving, saved, error

    public function mount($slug)
    {
        $this->authorize('bale-page.read');
        TenantConnectionService::ensureActive();

        $page = Page::whereSlug($slug)->first();

        if (!is_null($page)) {
            $this->id = $page->id;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
            $this->updated_at = $page->updated_at;
        } else {
            session()->flash('error', [
                'title' => __('Page Not Found!'),
            ]);
            $this->redirectRoute('bale.cms.pages.index', navigate: true);
        }
    }

    public function render()
    {
        return view('cms::livewire.pages.page.edit-page');
    }

    public function rules()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        return [
            'title' => 'required|string|min:3|max:50',
            'slug' => [
                'required',
                'string',
                Rule::unique($connection . '.pages', 'slug')->ignore($this->id),
            ],
        ];
    }

    public function update()
    {
        $this->authorize('bale-page.update');
        $this->validate();
        $this->autoSave();
        $this->redirectRoute('bale.cms.pages.index', navigate: true);
    }

    public function updated($propertyName)
    {
        $autoSaveFields = ['title', 'slug', 'content'];

        if (in_array($propertyName, $autoSaveFields)) {
            $this->autoSave();
        }
    }

    public function autoSave()
    {
        $this->saveStatus = 'saving';
        $this->dispatch('status-updated', status: 'saving');

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $page = Page::on($connection)
                ->find($this->id);

            if ($page) {
                $page->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

                $this->saveStatus = 'saved';
                $this->dispatch('status-updated', status: 'saved');

                $this->updated_at = now();
            }
        } catch (\Throwable $th) {
            $this->saveStatus = 'error';
            $this->dispatch('status-updated', status: 'error');
            info('Auto-save failed: ' . $th->getMessage());
        }
    }
}

<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Attributes\{Layout, Locked, Title};
use Bale\Cms\Models\Page;
use Bale\Cms\Services\TenantConnectionService;

#[Layout('cms::layouts.page-editor')]
#[Title('Bale | Edit Page')]
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

    public function mount($slug)
    {
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
                'title' => 'Page Not Found!',
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

    public function update($slug)
    {
        $this->slug = $slug['slug'];

        $this->validate();
        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', saved: true);

            (new Page)
                ->setConnection($connection)
                ->find($this->id)
                ->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

            DB::commit();
            session()->flash('success', 'Page Updated!');

            $this->redirectRoute('bale.cms.pages.index', navigate: true);

        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page update failed: ' . $th->getMessage());
        }
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'content') {
            $this->autoSave();
        }
    }

    public function autoSave()
    {
        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $page = (new Page)
                ->setConnection($connection)
                ->find($this->id);

            if ($page) {
                $page->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

                $this->dispatch('toast', message: 'Auto-saved successfully!', type: 'success');
                $this->updated_at = now();
            }
        } catch (\Throwable $th) {
            info('Auto-save failed: ' . $th->getMessage());
        }
    }
}
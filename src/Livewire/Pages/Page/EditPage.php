<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Jantinnerezo\LivewireAlert\LivewireAlert;
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
    public $locked;

    public function mount($slug)
    {
        TenantConnectionService::ensureActive();

        $page = Page::whereSlug($slug)->first();

        if (!is_null($page)) {
            $this->id = $page->id;
            $this->title = $page->title;
            $this->slug = $page->slug;
            $this->content = $page->content;
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
        // pastikan koneksi tenant aktif
        TenantConnectionService::ensureActive();

        // ambil nama koneksi tenant
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

    protected function messages()
    {
        return [
            'content.required' => 'The :attribute are missing.',
            'content.min' => 'The :attribute is too short.',
        ];
    }

    public function update(LivewireAlert $alert, $slug)
    {
        $this->slug = $slug['slug'];

        $this->validate();
        DB::beginTransaction();

        try {
            TenantConnectionService::ensureActive();
            $connection = TenantConnectionService::connection();

            $this->dispatch('disabling-button', saved: true);

            $page = (new Page)
                ->setConnection($connection)
                ->find($this->id)
                ->update([
                    'title' => $this->title,
                    'slug' => $this->slug,
                    'content' => $this->content,
                ]);

            DB::commit();

            $alert->title('Page Updated!')->position('top-end')->success()->toast()->show();

            // $this->redirectRoute('bale.cms.pages.index', navigate: true);

            $this->dispatch('disabling-button', params: false);
        } catch (\Throwable $th) {
            DB::rollBack();
            $this->dispatch('disabling-button', params: false);
            info('Page update failed: ' . $th->getMessage());
            $alert->title('Something wrong!')->position('top-end')->error()->toast()->show();

        }
    }
}
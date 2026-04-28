<?php

namespace Bale\Cms\Livewire\Pages\Page;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
    public $showSeo = false;
    public $saveStatus = 'editing'; // editing, saving, saved, error

    // SEO Properties
    public $seo_title;
    public $seo_description;
    public $seo_keywords;
    public $og_image;
    public $og_image_new;
    public $twitter_card = 'summary_large_image';
    public $no_index = false;
    public $no_follow = false;
    public $canonical_url;
    public $structured_data;

    public function mount($slug)
    {
        $this->authorize('bale-page.read');
        TenantConnectionService::ensureActive();

        $page = Page::whereSlug($slug)->first();

        if (!is_null($page)) {
            $this->id = $page->id;
            $this->title = $page->title ?? '';
            $this->slug = $page->slug ?? '';
            $this->content = $page->content ?? [];
            $this->updated_at = $page->updated_at;

            // Load SEO Meta
            $seo = $page->seoMeta;
            if ($seo) {
                $this->seo_title = $seo->title ?? '';
                $this->seo_description = $seo->description ?? '';
                $this->seo_keywords = $seo->keywords ?? '';
                $this->og_image = $seo->og_image;
                $this->twitter_card = $seo->twitter_card ?? 'summary_large_image';
                $this->no_index = (bool) $seo->no_index;
                $this->no_follow = (bool) $seo->no_follow;
                $this->canonical_url = $seo->canonical_url ?? '';
                $this->structured_data = $seo->structured_data ? json_encode($seo->structured_data, JSON_PRETTY_PRINT) : null;
            }
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

    public function deleteOgImage()
    {
        $this->authorize('bale-seo.update');
        $this->saveStatus = 'saving';
        if ($this->og_image) {
            \Illuminate\Support\Facades\Storage::disk('s3')->delete(session('bale_active_slug') . '/thumbnails/' . $this->og_image);
        }

        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        $page = Page::on($connection)->find($this->id);
        if ($page) {
            $page->updateSeoMeta(['og_image' => null]);
            $this->og_image = null;
        }

        $this->saveStatus = 'saved';
        $this->dispatch('status-updated', status: 'saved');
        $this->dispatch('post-status-reset');
    }

    public function updated($propertyName)
    {
        if ($propertyName === 'title') {
            $this->slug = Str::slug($this->title);
        }

        $autoSaveFields = [
            'title',
            'slug',
            'content',
            'seo_title',
            'seo_description',
            'seo_keywords',
            'twitter_card',
            'no_index',
            'no_follow',
            'canonical_url',
            'structured_data'
        ];

        if (in_array($propertyName, $autoSaveFields)) {
            $this->autoSave();
        }
    }

    public function updatedOgImageNew()
    {
        $this->authorize('bale-seo.update');
        try {
            if ($this->og_image) {
                \Illuminate\Support\Facades\Storage::disk('s3')->delete(session('bale_active_slug') . '/thumbnails/' . $this->og_image);
            }

            if ($this->og_image_new) {
                $extension = $this->og_image_new->getClientOriginalExtension();
                $filename = session('bale_active_slug') . '-seo-' . uniqid() . '.' . $extension;
                $finalPath = session('bale_active_slug') . '/thumbnails/' . $filename;

                \Illuminate\Support\Facades\Storage::disk('s3')->put($finalPath, $this->og_image_new->get());

                TenantConnectionService::ensureActive();
                $connection = TenantConnectionService::connection();

                $page = Page::on($connection)->find($this->id);
                if ($page) {
                    $page->updateSeoMeta(['og_image' => $filename]);
                    $this->og_image = $filename;
                }

                $this->og_image_new = null;
                $this->saveStatus = 'saved';
                $this->dispatch('status-updated', status: 'saved');
            }
        } catch (\Throwable $th) {
            $this->saveStatus = 'error';
            $this->dispatch('status-updated', status: 'error');
            info('SEO Image upload failed: ' . $th->getMessage());
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

                if (auth()->user()->can('bale-seo.update')) {
                    // Handle structured data JSON
                    $structuredData = null;
                    if ($this->structured_data) {
                        $structuredData = json_decode($this->structured_data, true);
                    }

                    $page->updateSeoMeta([
                        'title' => $this->seo_title,
                        'description' => $this->seo_description,
                        'keywords' => $this->seo_keywords,
                        'twitter_card' => $this->twitter_card,
                        'no_index' => $this->no_index,
                        'no_follow' => $this->no_follow,
                        'canonical_url' => $this->canonical_url,
                        'structured_data' => $structuredData,
                    ]);
                }

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

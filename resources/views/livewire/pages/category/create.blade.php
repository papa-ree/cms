<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title, Locked};
use Bale\Cms\Models\Category;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Category')]
    class extends Component {

    #[Locked]
    public $id;
    public $name = '';
    public $slug = '';
    public $isEdit = false;

    public function mount($slug = null)
    {
        if ($slug) {
            $this->isEdit = true;
            TenantConnectionService::ensureActive();
            $category = Category::where('slug', $slug)->firstOrFail();

            $this->id = $category->id;
            $this->name = $category->name;
            $this->slug = $category->slug;
        }
    }

    public function rules()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        return [
            'name' => 'required|string|max:50',
            'slug' => [
                'required',
                'string',
                'max:60',
                Rule::unique($connection . '.categories', 'slug')->ignore($this->id),
            ],
        ];
    }

    public function submit($data)
    {
        $this->authorize($this->isEdit ? 'bale-category.update' : 'bale-category.create');

        $this->name = $data['name'] ?? $this->name;
        $this->slug = $data['slug'] ?? $this->slug;
        $this->validate();

        TenantConnectionService::ensureActive();

        if ($this->isEdit) {
            Category::find($this->id)?->update([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            $message = __('Category updated successfully');
        } else {
            Category::create([
                'name' => $this->name,
                'slug' => $this->slug,
            ]);
            $message = __('Category created successfully');
        }

        $this->dispatch('toast', message: $message, type: 'success');
        $this->redirectRoute('bale.cms.categories.index', navigate: true);
    }
};
?>

<div>
    <x-core::page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8">
            <div class="max-w-xl mx-auto space-y-8">

                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
                        {{ $isEdit ? __('Edit Category') : __('Create Category') }}
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">
                        {{ $isEdit
    ? __('Update the category name and slug. Note that changing the slug may affect existing SEO rankings.')
    : __('Organize your content by creating categories. This helps users navigate and find relevant posts easily.') 
                        }}
                    </p>
                </div>

                <div class="mt-12">
                    <form @submit.prevent="$wire.call('submit', Object.fromEntries(new FormData($event.target)))"
                        x-data="{ categoryName: @entangle('name'), categorySlug: @entangle('slug') }" x-cloak>

                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="{{ __('Category Name') }}" wire:model='name' x-model="categoryName"
                                name="name" autofocus />
                            <x-core::input-error for="name" />
                        </div>

                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="{{ __('Category Slug') }}" wire:model='slug' name="slug"
                                x-slug="categoryName" x-model="categorySlug" />
                            <x-core::input-error for="slug" />
                        </div>

                        <div class="flex justify-center gap-4">
                            <x-core::secondary-button link label="{{ __('Cancel') }}"
                                href="{{ route('bale.cms.categories.index') }}" />
                            <x-core::button label="{{ $isEdit ? __('Update Category') : __('Create Category') }}"
                                spinner type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-core::page-container>
</div>
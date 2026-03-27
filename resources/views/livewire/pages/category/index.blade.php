<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title, Computed, Url};
use Livewire\WithPagination;
use Bale\Cms\Models\Category;
use Bale\Cms\Services\TenantConnectionService;
use Bale\Cms\Traits\HasSafeDelete;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Category Management')]
    class extends Component {
    use WithPagination, HasSafeDelete;

    protected string $modelClass = Category::class;

    #[Url(history: true)]
    public $query = '';

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        TenantConnectionService::ensureActive();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function updatedQuery()
    {
        $this->resetPage();
    }

    #[Computed]
    public function categories()
    {
        TenantConnectionService::ensureActive();
        return Category::query()
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%')
                ->orWhere('slug', 'like', '%' . $this->query . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
};
?>

<div>
    <x-core::page-header gradient :title="__('Category Management')" :subtitle="__('Manage your content categories for better organization')">
        <x-slot name="action">
            @can('bale-category.create')
                <x-core::button link href="{{ route('bale.cms.categories.create') }}" label="{{ __('Add Category') }}">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-5 h-5" />
                    </x-slot>
                </x-core::button>
            @endcan
        </x-slot>
    </x-core::page-header>

    <x-core::table :links="$this->categories" header>
        <x-slot name="thead">
            <tr>
                <x-core::table-th :label="__('Name')" sortBy="name" :sortField="$sortField" :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Slug')" sortBy="slug" :sortField="$sortField" :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Created At')" sortBy="created_at" :sortField="$sortField" :sortDirection="$sortDirection" />
                @canany(['bale-category.update', 'bale-category.delete'])
                    <x-core::table-th :label="__('Action')" />
                @endcanany
            </tr>
        </x-slot>
        <x-slot name="tbody">
            @forelse($this->categories as $category)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors" wire:key="row-{{ $category->id }}">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $category->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span class="px-2 py-1 text-xs font-mono text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md">
                            {{ $category->slug }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $category->created_at->format('d M Y') }}
                    </td>
                    @canany(['bale-category.update', 'bale-category.delete'])
                        <td class="px-6 py-4 whitespace-nowrap">
                            <livewire:core.shared-components.item-actions 
                                :editUrl="route('bale.cms.categories.edit', $category->slug)"
                                :deleteId="$category->id" 
                                wire:key="category-actions-{{ $category->id }}"
                                confirmMessage="{{ __('Are you sure you want to delete this category?') }}" />
                        </td>
                    @endcanany
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                        {{ __('No categories found.') }}
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-core::table>
</div>

<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Bale\Cms\Models\Category;
use Bale\Cms\Services\TenantConnectionService;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Category Management')]
    class extends Component {

    public function mount()
    {
        TenantConnectionService::ensureActive();
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

    <livewire:core-shared-components::data-table
        model="Bale\Cms\Models\Category"
        rowView="cms::livewire.pages.category.section.category-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'name',
                'label'    => __('Name'),
                'sortable' => true,
            ],
            [
                'key'      => 'slug',
                'label'    => __('Slug'),
                'sortable' => true,
                'hidden'   => 'sm',
            ],
            [
                'key'      => 'created_at',
                'label'    => __('Created At'),
                'sortable' => true,
                'hidden'   => 'lg',
            ],
            [
                'key'      => 'actions',
                'label'    => '',
                'sortable' => false,
            ],
        ]"
        :searchable="['name', 'slug']"
        sortField="name"
        sortDirection="asc"
        :perPage="20"
    />
</div>


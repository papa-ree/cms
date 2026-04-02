<div>
    <livewire:core-shared-components::data-table
        model="Bale\Cms\Models\Page"
        rowView="cms::livewire.pages.page.section.page-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'title',
                'label'    => __('Page Title'),
                'sortable' => true,
            ],
            [
                'key'      => 'type',
                'label'    => __('Type'),
                'sortable' => true,
                'hidden'   => 'lg',
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
        :searchable="['title', 'slug']"
        sortField="created_at"
        sortDirection="desc"
        :perPage="20"
    >
        <x-slot name="filters">
            <div class="space-y-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">{{ __('Filter by Type') }}</label>
                    <select wire:model.live="activeFilters.type"
                        class="block w-full py-2 px-3 border border-gray-200 bg-white rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-100 focus:border-indigo-400 sm:text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                        <option value="">{{ __('All Types') }}</option>
                        <option value="dynamic">{{ __('Dynamic') }}</option>
                        <option value="static">{{ __('Static') }}</option>
                    </select>
                </div>
            </div>
        </x-slot>
    </livewire:core-shared-components::data-table>
</div>


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
                'key'      => 'slug',
                'label'    => __('Slug'),
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

    </livewire:core-shared-components::data-table>
</div>


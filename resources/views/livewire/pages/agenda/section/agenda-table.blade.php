<div>
    <livewire:core-shared-components::data-table
        model="Bale\Cms\Models\Agenda"
        rowView="cms::livewire.pages.agenda.section.agenda-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'title',
                'label'    => __('Judul'),
                'sortable' => true,
            ],
            [
                'key'      => 'start_date',
                'label'    => __('Tanggal'),
                'sortable' => true,
                'hidden'   => 'md',
            ],
            [
                'key'      => 'location',
                'label'    => __('Lokasi'),
                'sortable' => false,
                'hidden'   => 'lg',
            ],
            [
                'key'      => 'recurrence',
                'label'    => __('Pengulangan'),
                'sortable' => false,
                'hidden'   => 'sm',
            ],
            [
                'key'      => 'is_visible',
                'label'    => __('Visibilitas'),
                'sortable' => true,
                'hidden'   => 'sm',
            ],
            [
                'key'      => 'actions',
                'label'    => '',
                'sortable' => false,
            ],
        ]"
        :searchable="['title', 'location']"
        sortField="start_date"
        sortDirection="asc"
        :perPage="20"
    >
    </livewire:core-shared-components::data-table>
</div>
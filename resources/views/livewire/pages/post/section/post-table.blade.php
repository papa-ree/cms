<div>
    <livewire:core-shared-components::data-table
        model="Bale\Cms\Models\Post"
        rowView="cms::livewire.pages.post.section.post-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'title',
                'label'    => __('Post Title'),
                'sortable' => true,
            ],
            [
                'key'      => 'category_slug',
                'label'    => __('Category'),
                'sortable' => true,
                'hidden'   => 'lg',
            ],
            [
                'key'      => 'author',
                'label'    => __('Author'),
                'sortable' => true,
                'hidden'   => 'md',
            ],
            [
                'key'      => 'published',
                'label'    => __('Status'),
                'sortable' => true,
                'hidden'   => 'sm',
            ],
            [
                'key'      => 'actions',
                'label'    => '',
                'sortable' => false,
            ],
        ]"
        :with="['userAuthor']"
        :searchable="['title']"
        sortField="created_at"
        sortDirection="desc"
        :perPage="20"
    >

        {{-- Filter slot dapat ditambahkan kembali setelah DataTable
             mendukung filterPublished property --}}

    </livewire:core-shared-components::data-table>
</div>

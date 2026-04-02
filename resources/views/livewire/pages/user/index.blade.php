<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Bale\Cms\Models\User;
use Bale\Cms\Services\TenantConnectionService;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | User Management')]
    class extends Component {

    public function mount()
    {
        TenantConnectionService::ensureActive();
        // Base permission for viewing users
        $this->authorize('bale-user.read');
    }
};
?>

<div>
    <x-core::page-header gradient :title="__('User Management')" :subtitle="__('Manage users who have access to this Bale instance')">
        <x-slot name="action">
            {{-- Action buttons if any in the future --}}
        </x-slot>
    </x-core::page-header>

    <livewire:core-shared-components::data-table
        model="Bale\Cms\Models\User"
        rowView="cms::livewire.pages.user.section.user-row"
        connectionResolver="Bale\Cms\Services\TenantConnectionService::resolveForQuery"
        :columns="[
            [
                'key'      => 'name',
                'label'    => __('User'),
                'sortable' => true,
            ],
            [
                'key'      => 'role',
                'label'    => __('Role'),
                'sortable' => true,
                'hidden'   => 'sm',
            ],
            [
                'key'      => 'username',
                'label'    => __('Username'),
                'sortable' => true,
                'hidden'   => 'md',
            ],
            [
                'key'      => 'created_at',
                'label'    => __('Joined'),
                'sortable' => true,
                'hidden'   => 'lg',
            ],
        ]"
        :searchable="['name', 'email', 'username']"
        sortField="name"
        sortDirection="asc"
        :perPage="15"
        :constraints="session('bale_active_user_role') !== 'root' ? ['role' => ['!=', 'root']] : []"
    />
</div>
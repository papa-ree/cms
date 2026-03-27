<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title, Computed};
use Livewire\WithPagination;
use Bale\Cms\Models\Role;
use Livewire\Attributes\Url;
use Bale\Cms\Services\TenantConnectionService;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Role Management')]
    class extends Component {
    use WithPagination;

    #[Url(history: true)]
    public $query = '';

    public $sortField = 'created_at';
    public $sortDirection = 'desc';

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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $this->authorize('bale-role.delete');
        Role::find($id)?->delete();
        $this->dispatch('toast', message: __('Role deleted successfully'), type: 'success');
    }

    #[Computed]
    public function roles()
    {
        TenantConnectionService::ensureActive();

        return Role::query()
            ->where('name', '!=', 'root')
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }
};
?>

<div>
    <x-core::page-header gradient :title="__('Role Management')" :subtitle="__('Manage access roles and limits for users')">
        <x-slot name="action">
            @can('bale-role.create')
                <x-core::button link href="{{ route('bale.cms.roles.create') }}" label="{{ __('Add New Role') }}">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-5 h-5" />
                    </x-slot>
                </x-core::button>
            @endcan
        </x-slot>
    </x-core::page-header>

    <x-core::table :links="$this->roles" header>
        <x-slot name="thead">
            <tr>
                <x-core::table-th :label="__('Name')" sortBy="name" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Guard')" sortBy="guard_name" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Description')" sortBy="description" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Created At')" sortBy="created_at" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                @canany(['bale-role.update', 'bale-role.delete'])
                    <x-core::table-th :label="__('Action')" />
                @endcanany
            </tr>
        </x-slot>
        <x-slot name="tbody">
            @forelse($this->roles as $role)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $role->name }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span
                            class="px-2 py-1 text-xs font-semibold text-gray-600 bg-gray-100 dark:bg-gray-700 dark:text-gray-300 rounded-full">
                            {{ $role->guard_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $role->description ?? '-' }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $role->created_at->format('d M Y') }}
                    </td>
                    @canany(['bale-role.update', 'bale-role.delete'])
                        <td class="px-6 py-4 whitespace-nowrap">
                            <livewire:core.shared-components.item-actions :editUrl="route('bale.cms.roles.edit', $role->id)"
                                :deleteId="$role->id" wire:key="item-actions-{{ $role->id }}"
                                confirmMessage="{{ __('Are you sure you want to delete this role?') }}" />
                        </td>
                    @endcanany
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                        {{ __('No roles found.') }}
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-core::table>
</div>

<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title, Computed};
use Livewire\WithPagination;
use Bale\Cms\Models\Permission;
use Livewire\Attributes\Url;
use Bale\Cms\Services\TenantConnectionService;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Permission Management')]
    class extends Component {
    use WithPagination;

    #[Url(history: true)]
    public $query = '';

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        TenantConnectionService::ensureActive();
        $this->authorize('bale-role.read'); // Using role.read as a base permission for viewing permissions
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
    public function permissions()
    {
        TenantConnectionService::ensureActive();

        return Permission::query()
            ->when($this->query, fn($query) => $query->where('name', 'like', '%' . $this->query . '%'))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }
};
?>

<div>
    <x-core::page-header gradient :title="__('Permission Management')" :subtitle="__('View system permissions and their configurations')">
        <x-slot name="action">
            {{-- No create action for now as per user request (read only) --}}
        </x-slot>
    </x-core::page-header>

    <div class="mb-6 max-w-md">
        <x-core::input type="text" wire:model.live.debounce.300ms="query"
            placeholder="{{ __('Search permissions...') }}" />
    </div>

    <x-core::table :links="$this->permissions" header>
        <x-slot name="thead">
            <tr>
                <x-core::table-th :label="__('Name')" sortBy="name" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Guard')" sortBy="guard_name" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Created At')" sortBy="created_at" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
            </tr>
        </x-slot>
        <x-slot name="tbody">
            @forelse($this->permissions as $permission)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                        <div class="flex items-center gap-2">
                            <span
                                class="p-1.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-md">
                                <x-lucide-shield class="w-4 h-4" />
                            </span>
                            {{ $permission->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        <span
                            class="px-2 py-1 text-xs font-semibold text-blue-600 bg-blue-50 dark:bg-blue-900/40 dark:text-blue-300 rounded-full border border-blue-100 dark:border-blue-800">
                            {{ $permission->guard_name }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $permission->created_at->format('d M Y H:i') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-full mb-4">
                                <x-lucide-shield-off class="w-12 h-12 text-gray-300 dark:text-gray-600" />
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No permissions found') }}
                            </h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-1">
                                {{ __('We couldn\'t find any permissions matching your search.') }}
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-core::table>
</div>

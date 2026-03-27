<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title, Computed};
use Livewire\WithPagination;
use Bale\Cms\Models\User;
use Livewire\Attributes\Url;
use Bale\Cms\Services\TenantConnectionService;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | User Management')]
    class extends Component {
    use WithPagination;

    #[Url(history: true)]
    public $query = '';

    public $sortField = 'name';
    public $sortDirection = 'asc';

    public function mount()
    {
        TenantConnectionService::ensureActive();
        // Base permission for viewing users
        $this->authorize('bale-user.read');
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
    public function users()
    {
        TenantConnectionService::ensureActive();

        return User::query()
            ->when($this->query, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->query . '%')
                        ->orWhere('email', 'like', '%' . $this->query . '%')
                        ->orWhere('username', 'like', '%' . $this->query . '%');
                });
            })
            ->when(session('bale_active_user_role') !== 'root', function ($query) {
                $query->where('role', '!=', 'root');
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(15);
    }
};
?>

<div>
    <x-core::page-header gradient :title="__('User Management')" :subtitle="__('Manage users who have access to this Bale instance')">
        <x-slot name="action">
            {{-- Action buttons if any in the future --}}
        </x-slot>
    </x-core::page-header>

    <div class="mb-6 max-w-md">
        <x-core::input type="text" wire:model.live.debounce.300ms="query"
            placeholder="{{ __('Search users by name, email, or username...') }}" />
    </div>

    <x-core::table :links="$this->users" header>
        <x-slot name="thead">
            <tr>
                <x-core::table-th :label="__('User')" sortBy="name" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Role')" />
                <x-core::table-th :label="__('Username')" sortBy="username" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
                <x-core::table-th :label="__('Joined')" sortBy="created_at" :sortField="$sortField"
                    :sortDirection="$sortDirection" />
            </tr>
        </x-slot>
        <x-slot name="tbody">
            @forelse($this->users as $user)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex flex-wrap gap-1">
                            @if($user->role)
                                <span
                                    class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 dark:bg-indigo-900/40 dark:text-indigo-300 rounded-md border border-indigo-100 dark:border-indigo-800">
                                    {{ $user->role }}
                                </span>
                            @else
                                <span class="text-xs text-gray-400 italic">{{ __('No role assigned') }}</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $user->username }}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ $user->created_at }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="p-4 bg-gray-50 dark:bg-gray-800 rounded-full mb-4">
                                <x-lucide-users class="w-12 h-12 text-gray-300 dark:text-gray-600" />
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ __('No users found') }}</h3>
                            <p class="text-gray-500 dark:text-gray-400 max-w-xs mx-auto mt-1">
                                {{ __('We couldn\'t find any users matching your search.') }}
                            </p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </x-slot>
    </x-core::table>
</div>
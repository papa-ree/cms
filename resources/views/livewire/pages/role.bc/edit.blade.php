<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Bale\Cms\Models\Role;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Edit Role')]
    class extends Component {
    public $roleId;
    public Role $role;

    public $name;
    public $guard_name;
    public $description;

    public function mount($roleId)
    {
        TenantConnectionService::ensureActive();

        $this->roleId = $roleId;
        $this->role = Role::findOrFail($roleId);

        $this->name = $this->role->name;
        $this->guard_name = $this->role->guard_name;
        $this->description = $this->role->description;
    }

    public function rules()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        return [
            'name' => [
                'required',
                'string',
                Rule::unique($connection . '.roles', 'name')
                    ->where('guard_name', $this->guard_name)
                    ->ignore($this->roleId),
            ],
            'guard_name' => 'required|string',
            'description' => 'nullable|string',
        ];
    }

    public function update()
    {
        $this->authorize('bale-role.update');
        $this->validate();

        $this->dispatch('disabling-button', params: true);

        try {
            $this->role->update([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'description' => $this->description,
            ]);

            $this->dispatch('toast', message: __('Role updated successfully'), type: 'success');
            return $this->redirectRoute('bale.cms.roles.index', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('disabling-button', params: false);
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }
};
?>

<div>
    <x-core::breadcrumb :items="[['label' => __('Roles'), 'route' => 'bale.cms.roles.index']]" :active="__('Edit Role')" />

    <div class="max-w-4xl mx-auto mt-6">
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xl overflow-hidden">
            <div
                class="p-6 border-b border-gray-100 dark:border-slate-800 bg-linear-to-r from-amber-50/50 to-orange-50/50 dark:from-amber-900/10 dark:to-orange-900/10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __('Edit Role') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ __('Modify existing role and guard mapping') }}
                </p>
            </div>

            <form wire:submit="update" class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-core::label for="name" :value="__('Role Name *')" />
                        <x-core::input id="name" type="text" wire:model.live="name" required />
                        @error('name')<x-core::input-error :message="$message" />@enderror
                    </div>
                    <div>
                        <x-core::label for="guard_name" :value="__('Guard Name *')" />
                        <x-core::input id="guard_name" type="text" wire:model="guard_name" required />
                        @error('guard_name')<x-core::input-error :message="$message" />@enderror
                    </div>
                </div>

                <div>
                    <x-core::label for="description" :value="__('Description')" />
                    <textarea id="description" wire:model="description" rows="3"
                        class="w-full mt-1 border-gray-300 dark:border-slate-700 dark:bg-slate-900 dark:text-white rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    @error('description')<x-core::input-error :message="$message" />@enderror
                </div>

                <div class="pt-6 border-t border-gray-100 dark:border-slate-800 flex items-center justify-between">
                    <x-core::secondary-button link href="{{ route('bale.cms.roles.index') }}"
                        label="{{ __('Cancel') }}" />
                    <x-core::button type="submit" label="{{ __('Update Role') }}" spinner="update">
                        <x-slot name="icon"><x-lucide-check class="w-4 h-4" /></x-slot>
                    </x-core::button>
                </div>
            </form>
        </div>
    </div>
</div>

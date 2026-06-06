<?php

use Livewire\Component;
use Livewire\Attributes\{Layout, Title};
use Bale\Cms\Models\Role;
use Bale\Cms\Services\TenantConnectionService;
use Illuminate\Validation\Rule;

new #[Layout('cms::layouts.app')]
    #[Title('Bale | Create Role')]
    class extends Component {
    public $name = '';
    public $guard_name = 'web';
    public $description = '';

    public function mount()
    {
        TenantConnectionService::ensureActive();
    }

    public function rules()
    {
        TenantConnectionService::ensureActive();
        $connection = TenantConnectionService::connection();

        return [
            'name' => [
                'required',
                'string',
                Rule::unique($connection . '.roles', 'name')->where('guard_name', $this->guard_name),
            ],
        ];
    }

    public function save()
    {
        $this->authorize('bale-role.create');
        $this->validate();

        $this->dispatch('disabling-button', params: true);

        try {
            Role::create([
                'name' => $this->name,
                'guard_name' => $this->guard_name,
                'description' => $this->description,
            ]);

            $this->dispatch('toast', message: __('Role created successfully'), type: 'success');
            return $this->redirectRoute('bale.cms.roles.index', navigate: true);
        } catch (\Exception $e) {
            $this->dispatch('disabling-button', params: false);
            $this->dispatch('toast', message: $e->getMessage(), type: 'error');
        }
    }
};
?>

<div>
    <x-core::breadcrumb :items="[['label' => __('Roles'), 'route' => 'bale.cms.roles.index']]" :active="__('Create Role')" />

    <div class="max-w-4xl mx-auto mt-6">
        <div
            class="bg-white dark:bg-slate-900 rounded-2xl border border-gray-100 dark:border-slate-800 shadow-xl overflow-hidden">
            <div
                class="p-6 border-b border-gray-100 dark:border-slate-800 bg-linear-to-r from-indigo-50/50 to-purple-50/50 dark:from-indigo-900/10 dark:to-purple-900/10">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                    {{ __('Create New Role') }}
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ __('Define a new role and its guard mapping') }}
                </p>
            </div>

            <form wire:submit="save" class="p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <x-core::label for="name" :value="__('Role Name *')" />
                        <x-core::input id="name" type="text" wire:model.live="name" required
                            placeholder="e.g. Content Editor" />
                        @error('name')<x-core::input-error :message="$message" />@enderror
                    </div>
                    <div>
                        <x-core::label for="guard_name" :value="__('Guard Name *')" />
                        <x-core::input id="guard_name" type="text" wire:model="guard_name" required />
                        <p class="mt-1 text-xs text-gray-500">{{ __('Default is typically web') }}</p>
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
                    <x-core::button type="submit" label="{{ __('Save Role') }}" spinner="save">
                        <x-slot name="icon"><x-lucide-check class="w-4 h-4" /></x-slot>
                    </x-core::button>
                </div>
            </form>
        </div>
    </div>
</div>

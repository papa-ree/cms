<div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Sections
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                Manage all your existing sections or add a new one.
            </p>
        </div>

        <x-core::button link href="{{ route('bale.cms.sections.create') }}" label="Create Section" class="gap-x-2">
            <x-slot name="icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </x-slot>
        </x-core::button>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <livewire:cms.pages.section.section.section-table />
    </div>
</div>
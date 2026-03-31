<header
    class="sticky top-0 z-50 w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <nav class="flex items-center justify-between px-4 py-3 mx-auto sm:px-6 md:px-8"
        aria-label="Post Editor Navigation">
        {{-- Left: Back Button --}}
        <div class="flex items-center gap-3">
            <a href="/cms/posts" wire:navigate.hover
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all">
                <x-lucide-arrow-left class="w-4 h-4" />
                <span class="hidden sm:inline">{{ __('Back to Posts') }}</span>
                <span class="sm:hidden">{{ __('Back') }}</span>
            </a>
        </div>

        {{-- Center: Hidden --}}
        <div class="hidden md:block"></div>

        {{-- Right: Status & Save Button --}}
        <div class="flex items-center gap-2 sm:gap-4">
            {{-- Status Indicator (Always visible) --}}
            <div
                class="flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 rounded-full">
                <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">{{ __('Editing') }}</span>
            </div>

            {{-- Desktop Save Button --}}
            <div class="hidden md:block" x-data="{ isSaving: false }" x-on:submit.window="isSaving = true"
                x-on:save-complete.window="isSaving = false">
                <x-core::button type="submit" form="formPost" :label="__('Save Post')"
                    class="font-bold shadow-indigo-500/20" x-bind:disabled="isSaving">
                    <x-slot name="icon">
                        <x-lucide-save class="w-4 h-4" />
                    </x-slot>
                </x-core::button>
            </div>
        </div>
    </nav>

    {{-- Mobile Bottom Bar with Tools --}}
    <div class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border-t border-gray-200 dark:border-gray-700 shadow-[0_-10px_20px_rgba(0,0,0,0.05)]"
        x-data="{ isSaving: false }" x-on:submit.window="isSaving = true" x-on:save-complete.window="isSaving = false">
        <div class="flex items-center justify-end gap-3 px-4 py-3">
            {{-- Tools for Mobile --}}
            {{-- <div
                class="flex items-center gap-1 p-1 bg-gray-100 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                <x-core::dark-mode-toggle />
                <livewire:core.shared-components.locale-dropdown />
            </div> --}}

            <x-core::button type="submit" form="formPost" :label="__('Save Post')"
                class="font-bold shadow-indigo-500/20" x-bind:disabled="isSaving">
                <x-slot name="icon">
                    <x-lucide-save class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>
    </div>
</header>
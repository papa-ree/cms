<header
    class="sticky top-0 z-50 w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <nav class="flex items-center justify-between px-4 py-3 mx-auto sm:px-6 md:px-8"
        aria-label="Post Editor Navigation">
        {{-- Left: Back Button --}}
        <div class="flex items-center gap-3">
            <a href="/cms/posts" wire:navigate.hover
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-300 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-lg transition-all">
                <x-lucide-arrow-left class="w-4 h-4" />
                <span class="hidden sm:inline">Back to Posts</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>

        {{-- Center: Title (Hidden on mobile) --}}
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
            <x-lucide-file-edit class="w-4 h-4 text-gray-600 dark:text-gray-400" />
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Post Editor</span>
        </div>

        {{-- Right: Save Button (Hidden on mobile) --}}
        <div class="hidden md:block" x-data="{ isSaving: false }" x-on:submit.window="isSaving = true"
            x-on:save-complete.window="isSaving = false">
            <x-core::button type="submit" form="formPost" label="Save Post" class="font-semibold"
                x-bind:disabled="isSaving">
                <x-slot name="icon">
                    <x-lucide-save class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>

        {{-- Mobile: Menu Button (if needed) --}}
        <div class="md:hidden">
            <div
                class="flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 border border-blue-300 dark:border-blue-700 rounded-full">
                <div class="w-2 h-2 bg-blue-600 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Editing</span>
            </div>
        </div>
    </nav>

    {{-- Mobile Bottom Bar with Save Button --}}
    <div class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg"
        x-data="{ isSaving: false }" x-on:submit.window="isSaving = true" x-on:save-complete.window="isSaving = false">
        <div class="px-4 py-3">
            <x-core::button type="submit" form="formPost" label="Save Post" class="w-full font-semibold"
                x-bind:disabled="isSaving">
                <x-slot name="icon">
                    <x-lucide-save class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>
    </div>
</header>
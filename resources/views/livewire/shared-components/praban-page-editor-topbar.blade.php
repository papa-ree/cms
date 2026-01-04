<header
    class="sticky top-0 z-50 w-full bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm">
    <nav class="flex items-center justify-between px-4 py-3 mx-auto sm:px-6 md:px-8"
        aria-label="Page Editor Navigation">
        {{-- Left: Back Button --}}
        <div class="flex items-center gap-3">
            <a href="/cms/pages" wire:navigate.hover
                class="inline-flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 hover:text-emerald-600 dark:text-gray-300 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg transition-all">
                <x-lucide-arrow-left class="w-4 h-4" />
                <span class="hidden sm:inline">Back to Pages</span>
                <span class="sm:hidden">Back</span>
            </a>
        </div>

        {{-- Center: Title (Hidden on mobile) --}}
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
            <x-lucide-file-edit class="w-4 h-4 text-gray-600 dark:text-gray-400" />
            <span class="text-sm font-semibold text-gray-900 dark:text-white">Page Editor</span>
        </div>

        {{-- Right: Save Button (Hidden on mobile) --}}
        <div class="hidden md:block">
            <x-core::button type="submit" form="formPage" label="Save Page" class="font-semibold">
                <x-slot name="icon">
                    <x-lucide-save class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>

        {{-- Mobile: Menu Button (if needed) --}}
        <div class="md:hidden">
            <div
                class="flex items-center gap-2 px-3 py-1.5 bg-emerald-100 dark:bg-emerald-900/30 border border-emerald-300 dark:border-emerald-700 rounded-full">
                <div class="w-2 h-2 bg-emerald-600 rounded-full animate-pulse"></div>
                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Editing</span>
            </div>
        </div>
    </nav>

    {{-- Mobile Bottom Bar with Save Button --}}
    <div
        class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg">
        <div class="px-4 py-3">
            <x-core::button type="submit" form="formPage" label="Save Page" class="w-full font-semibold">
                <x-slot name="icon">
                    <x-lucide-save class="w-4 h-4" />
                </x-slot>
            </x-core::button>
        </div>
    </div>
</header>
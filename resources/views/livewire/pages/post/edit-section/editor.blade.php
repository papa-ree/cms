{{-- RIGHT: EditorJS Content Area --}}
<div class="lg:col-span-5">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        {{-- Editor Header --}}
        <div
            class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4 bg-linear-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 md:gap-3">
                <div
                    class="p-2 md:p-2.5 bg-linear-to-br from-amber-500 to-amber-600 rounded-lg shadow-md">
                    <x-lucide-file-edit class="w-4 md:w-5 h-4 md:h-5 text-white" />
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                        {{ __('Content Editor') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ __('Write your post content') }}
                    </p>
                </div>
            </div>
            <div
                class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-full">
                <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                <span
                    class="text-xs font-medium text-green-700 dark:text-green-400">{{ __('Dynamic Saving') }}</span>
            </div>
        </div>

        {{-- Editor Toolbar Guide --}}
        <div
            class="px-6 py-3 bg-blue-50 dark:bg-blue-900/10 border-b border-blue-200 dark:border-blue-800/50">
            <div class="flex items-start gap-2 text-xs text-blue-700 dark:text-blue-400">
                <x-lucide-lightbulb class="w-4 h-4 mt-0.5" />
                <div>
                    <span class="font-semibold">{{ __('Quick tip:') }}</span>
                    <span>{{ __('Press') }} <kbd
                            class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-800 rounded text-blue-800 dark:text-blue-300 font-mono">{{ __('Content Editor') }}</kbd>
                        {{ __('to start typing, and use') }} <kbd
                            class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-blue-300 dark:border-blue-800 rounded text-blue-800 dark:text-blue-300 font-mono">+</kbd>
                        {{ __('button on the left.') }}</span>
                </div>
            </div>
        </div>

        {{-- EditorJS Container --}}
        <div wire:ignore id="editorjs"
            class="px-6 py-8 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 min-h-[70vh] max-h-[70vh] overflow-y-auto scrollbar-gutter-both prose prose-slate dark:prose-invert max-w-none
        scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
        </div>

        <x-core::input-error for="content" />

        {{-- Editor Footer --}}
        <div
            class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                <div class="items-center gap-4 hidden sm:flex">
                    <div class="flex items-center gap-1.5">
                        <x-lucide-text class="w-3.5 h-3.5" />
                        <span>{{ __('Editor') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <x-lucide-image class="w-3.5 h-3.5" />
                        <span>{{ __('Image support') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <x-lucide-list class="w-3.5 h-3.5" />
                        <span>{{ __('Lists') }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <x-lucide-table class="w-3.5 h-3.5" />
                        <span>{{ __('Tables') }}</span>
                    </div>
                </div>
                <span class="text-gray-500 text-pretty">{{ __('Last edited:') }}
                    {{ $updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
</div>

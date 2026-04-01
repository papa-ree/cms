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

        {{-- Right: Status Only --}}
        <div class="flex items-center gap-2 sm:gap-4">
            {{-- Status Indicator (Dynamic) --}}
            <div
                class="flex items-center gap-2 px-3 py-1.5 rounded-full border transition-all duration-300
                {{ $saveStatus === 'saving' ? 'bg-blue-100 border-blue-300 dark:bg-blue-900/30 dark:border-blue-700' : '' }}
                {{ $saveStatus === 'saved' ? 'bg-emerald-100 border-emerald-300 dark:bg-emerald-900/30 dark:border-emerald-700' : '' }}
                {{ $saveStatus === 'error' ? 'bg-rose-100 border-rose-300 dark:bg-rose-900/30 dark:border-rose-700' : '' }}
                {{ $saveStatus === 'editing' ? 'bg-gray-100 border-gray-300 dark:bg-gray-800 dark:border-gray-700' : '' }}
                ">
                
                {{-- Dot Indicator --}}
                <div class="w-2 h-2 rounded-full 
                    {{ $saveStatus === 'saving' ? 'bg-blue-600 animate-pulse' : '' }}
                    {{ $saveStatus === 'saved' ? 'bg-emerald-600' : '' }}
                    {{ $saveStatus === 'error' ? 'bg-rose-600' : '' }}
                    {{ $saveStatus === 'editing' ? 'bg-gray-400' : '' }}
                "></div>

                <span class="text-xs font-medium 
                    {{ $saveStatus === 'saving' ? 'text-blue-700 dark:text-blue-400' : '' }}
                    {{ $saveStatus === 'saved' ? 'text-emerald-700 dark:text-emerald-400' : '' }}
                    {{ $saveStatus === 'error' ? 'text-rose-700 dark:text-rose-400' : '' }}
                    {{ $saveStatus === 'editing' ? 'text-gray-600 dark:text-gray-400' : '' }}
                ">
                    @if($saveStatus === 'saving') {{ __('Saving...') }}
                    @elseif($saveStatus === 'saved') {{ __('Saved') }}
                    @elseif($saveStatus === 'error') {{ __('Error') }}
                    @else {{ __('Editing') }}
                    @endif
                </span>
            </div>
        </div>
    </nav>
</header>
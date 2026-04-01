<div>
    @can('bale-post.update')
        <button wire:click="toggle" type="button"
            class="group inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-[11px] font-semibold transition-all focus:outline-none
                {{ $published
                    ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/40 border border-emerald-200/60 dark:border-emerald-700/40'
                    : 'bg-gray-100 text-gray-500 hover:bg-gray-200 dark:bg-gray-700/60 dark:text-gray-400 dark:hover:bg-gray-700 border border-gray-200/60 dark:border-gray-600/40'
                }}">
            <span class="relative flex size-1.5 shrink-0">
                @if($published)
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-60"></span>
                    <span class="relative inline-flex rounded-full size-1.5 bg-emerald-500"></span>
                @else
                    <span class="relative inline-flex rounded-full size-1.5 bg-gray-400 dark:bg-gray-500"></span>
                @endif
            </span>
            {{ $published ? __('Published') : __('Draft') }}
        </button>
    @else
        {{-- View only --}}
        <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-[11px] font-semibold
            {{ $published
                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/20 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-700/40'
                : 'bg-gray-100 text-gray-500 dark:bg-gray-700/60 dark:text-gray-400 border border-gray-200/60 dark:border-gray-600/40'
            }}">
            <span class="relative inline-flex rounded-full size-1.5 {{ $published ? 'bg-emerald-500' : 'bg-gray-400 dark:bg-gray-500' }}"></span>
            {{ $published ? __('Published') : __('Draft') }}
        </span>
    @endcan
</div>

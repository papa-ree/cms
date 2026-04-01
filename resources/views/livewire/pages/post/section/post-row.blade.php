<tr wire:key="post-row-{{ $record->getKey() }}"
    class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-150">

    {{-- Title + Thumbnail --}}
    <td class="px-4 py-3.5 w-full max-w-0 sm:max-w-none sm:w-auto">
        <div class="flex items-center gap-3">
            {{-- Thumbnail --}}
            @if($record->thumbnail)
                <img class="size-9 rounded-lg object-cover shrink-0 ring-1 ring-gray-200 dark:ring-gray-700"
                    loading="lazy"
                    src="{{ $record->thumbnail_url }}"
                    alt="{{ $record->title }}">
            @else
                <div class="size-9 rounded-lg bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center shrink-0">
                    <svg class="size-4 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd"/>
                    </svg>
                </div>
            @endif

            {{-- Text --}}
            <div class="min-w-0 flex-1">
                <a href="{{ route('bale.cms.posts.edit', $record->slug) }}"
                    class="block text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors truncate">
                    {{ Str::of($record->title)->words(6, '...') }}
                </a>

                {{-- Mobile-only collapsed info --}}
                <dl class="font-normal mt-0.5 space-y-0.5">
                    {{-- Category (hidden lg+) --}}
                    <dd class="text-xs text-gray-500 lg:hidden">
                        @if($record->category_slug)
                            <span class="inline-block px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                {{ $record->category_slug }}
                            </span>
                        @endif
                    </dd>
                    {{-- Author (hidden md+) --}}
                    <dd class="text-xs text-gray-400 dark:text-gray-500 md:hidden">
                        {{ __('by') }} {{ $record->userAuthor?->name ?? __('unknown') }}
                    </dd>
                    {{-- Status (hidden sm+) --}}
                    <dd class="sm:hidden mt-1">
                        <livewire:cms.shared-components.post-status-toggle
                            :postId="$record->id"
                            :published="(bool) $record->published"
                            wire:key="toggle-mobile-{{ $record->id }}" />
                    </dd>
                </dl>
            </div>
        </div>
    </td>

    {{-- Category (desktop) --}}
    <td class="hidden px-4 py-3.5 text-sm lg:table-cell">
        @if($record->category_slug)
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/20 dark:text-blue-400 border border-blue-100 dark:border-blue-800/40">
                {{ $record->category_slug }}
            </span>
        @else
            <span class="text-xs text-gray-400 dark:text-gray-600">—</span>
        @endif
    </td>

    {{-- Author (desktop) --}}
    <td class="hidden px-4 py-3.5 md:table-cell">
        <div class="flex items-center gap-2">
            <div class="size-6 rounded-full bg-linear-to-br from-indigo-400 to-purple-500 flex items-center justify-center shrink-0">
                <span class="text-white text-[9px] font-bold">
                    {{ strtoupper(substr($record->userAuthor?->name ?? 'U', 0, 1)) }}
                </span>
            </div>
            <div class="min-w-0">
                <p class="text-xs font-medium text-gray-700 dark:text-gray-300 truncate">
                    {{ $record->userAuthor?->name ?? __('unknown') }}
                </p>
                <p class="text-[10px] text-gray-400 dark:text-gray-500">
                    {{ $record->created_at }}
                </p>
            </div>
        </div>
    </td>

    {{-- Status (desktop) --}}
    <td class="hidden px-4 py-3.5 sm:table-cell">
        <livewire:cms.shared-components.post-status-toggle
            :postId="$record->id"
            :published="(bool) $record->published"
            wire:key="toggle-{{ $record->id }}" />
        @if($record->published && $record->published_at)
            <p class="text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                {{ $record->published_at }}
            </p>
        @endif
    </td>

    {{-- Actions --}}
    <td class="px-4 py-3.5 whitespace-nowrap w-px">
        @canany(['bale-post.update', 'bale-post.delete'])
            <livewire:core.shared-components.item-actions
                :editUrl="route('bale.cms.posts.edit', $record->slug)"
                :deleteId="$record->id"
                :navigate="false"
                confirmMessage="{{ __('Yakin ingin menghapus post ini?') }}"
                wire:key="item-actions-{{ $record->id }}" />
        @endcanany
    </td>

</tr>

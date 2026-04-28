<tr wire:key="page-row-{{ $record->id }}"
    class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-150">
    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
        <div class="grow">
            <a href="{{ route('bale.cms.pages.edit', $record->slug) }}"
                class="block text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                {{ $record->title }}
            </a>
            <dl class="font-normal lg:hidden">
                {{-- Slug --}}
                <dt class="sr-only">{{ __('Slug') }}</dt>
                <dd class="text-gray-700 dark:text-gray-400 truncate">
                    <div x-data="{ copied: false }" class="flex items-center gap-1">
                        <span
                            class="inline-flex items-center px-1.5 py-0.5 rounded-md bg-gray-50 dark:bg-gray-900/50 text-[10px] font-mono lowercase text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                            /{{ $record->slug ?? ('--') }}
                        </span>
                        <button @click="$clipboard('/page/{{ $record->slug }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                            class="p-1 text-gray-400 hover:text-emerald-600 transition-colors">
                            <x-lucide-copy x-show="!copied" class="w-3 h-3" />
                            <x-lucide-check x-show="copied" class="w-3 h-3 text-emerald-600" style="display: none" />
                        </button>
                    </div>
                </dd>

                {{-- Created At --}}
                <dt class="sr-only lg:hidden">{{ __('Created At') }}</dt>
                <dd class="text-gray-500 truncate lg:hidden mt-0.5">
                    <span class="block text-[11px] text-gray-500 dark:text-gray-500">{{ __('created at') }}
                        {{ $record->created_at }}</span>
                </dd>
            </dl>
        </div>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell">
        <div x-data="{ copied: false }" class="flex items-center gap-1.5 group">
            <span
                class="px-2 py-0.5 text-[11px] font-mono lowercase rounded-md bg-emerald-50 text-emerald-700 border border-emerald-100 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800/50">
                /{{ $record->slug }}
            </span>
            <button @click="$clipboard('/page/{{ $record->slug }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                class="p-1 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors opacity-0 group-hover:opacity-100"
                title="{{ __('Copy URL') }}">
                <x-lucide-copy x-show="!copied" class="w-3.5 h-3.5" />
                <x-lucide-check x-show="copied" class="w-3.5 h-3.5 text-emerald-600" style="display: none" />
            </button>
        </div>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell">
        {{ $record->created_at }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-px">
        @canany(['bale-page.update', 'bale-page.delete'])
            <livewire:core.shared-components.item-actions :editUrl="route('bale.cms.pages.edit', $record->slug)"
                :deleteId="$record->id" :navigate="false" wire:key="page-actions-{{ $record->id }}"
                confirmMessage="{{ __('Are you sure you want to delete this page?') }}" />
        @endcanany
    </td>
</tr>
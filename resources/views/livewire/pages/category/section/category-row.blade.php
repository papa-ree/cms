<tr wire:key="category-row-{{ $record->id }}"
    class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
    <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
        <div class="grow">
            {{ $record->name }}
            <dl class="font-normal lg:hidden">
                {{-- Slug (Hidden SM) --}}
                <dt class="sr-only sm:hidden">{{ __('Slug') }}</dt>
                <dd class="text-xs text-gray-500 sm:hidden mt-0.5">
                    <span class="px-1.5 py-0.5 rounded-sm bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-mono text-[10px]">
                        {{ $record->slug }}
                    </span>
                </dd>

                {{-- Created At (Hidden LG) --}}
                <dt class="sr-only lg:hidden">{{ __('Created At') }}</dt>
                <dd class="text-gray-500 truncate lg:hidden mt-1">
                    <span class="block text-[10px] text-gray-500">{{ __('created at') }}
                        {{ $record->created_at }}</span>
                </dd>
            </dl>
        </div>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 sm:table-cell">
        <span class="px-2 py-1 text-xs font-mono text-blue-600 bg-blue-50 dark:bg-blue-900/30 dark:text-blue-400 rounded-md">
            {{ $record->slug }}
        </span>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell">
        {{ $record->created_at }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-px">
        @canany(['bale-category.update', 'bale-category.delete'])
            <livewire:core.shared-components.item-actions 
                :editUrl="route('bale.cms.categories.edit', $record->slug)"
                :deleteId="$record->id" 
                :navigate="false"
                wire:key="category-actions-{{ $record->id }}"
                confirmMessage="{{ __('Are you sure you want to delete this category?') }}" />
        @endcanany
    </td>
</tr>

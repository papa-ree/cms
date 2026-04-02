<tr wire:key="page-row-{{ $record->id }}"
    class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-150">
    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
        <div class="grow">
            <a href="{{ route('bale.cms.pages.edit', $record->slug) }}"
                class="block text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">
                {{ $record->title }}
            </a>
            <dl class="font-normal lg:hidden">
                {{-- Type --}}
                <dt class="sr-only">{{ __('Type') }}</dt>
                <dd class="text-gray-700 dark:text-gray-400 truncate">
                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-sm bg-gray-100 dark:bg-gray-700 text-[10px] font-medium uppercase tracking-wider text-gray-600 dark:text-gray-300">
                        {{ $record->type ?? __('static') }}
                    </span>
                </dd>

                {{-- Created At --}}
                <dt class="sr-only lg:hidden">{{ __('Created At') }}</dt>
                <dd class="text-gray-500 truncate lg:hidden mt-0.5">
                    <span class="block text-[11px] text-gray-500 dark:text-gray-500">{{ __('created at') }} {{ $record->created_at }}</span>
                </dd>
            </dl>
        </div>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell">
        <span class="px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
            {{ $record->type }}
        </span>
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 lg:table-cell">
        {{ $record->created_at }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap w-px">
        @canany(['bale-page.update', 'bale-page.delete'])
            <livewire:core.shared-components.item-actions
                :editUrl="route('bale.cms.pages.edit', $record->slug)"
                :deleteId="$record->id"
                :navigate="false"
                wire:key="page-actions-{{ $record->id }}"
                confirmMessage="{{ __('Are you sure you want to delete this page?') }}"
            />
        @endcanany
    </td>
</tr>

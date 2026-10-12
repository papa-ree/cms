<tr wire:key="agenda-row-{{ $record->getKey() }}"
    class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-150">

    {{-- Primary: Title --}}
    <td class="px-4 py-3.5 w-full max-w-0 sm:max-w-none sm:w-auto">
        <div class="min-w-0">
            <a href="{{ route('bale.cms.agendas.edit', $record->id) }}"
                class="block text-sm font-medium text-gray-900 dark:text-gray-100 hover:text-purple-600 dark:hover:text-purple-400 transition-colors truncate">
                {{ $record->title }}
            </a>

            {{-- Mobile-only collapsed info --}}
            <dl class="font-normal mt-0.5 space-y-0.5">
                {{-- Category (hidden lg+) --}}
                @if($record->category)
                    <dd class="text-xs text-gray-500 lg:hidden">
                        <span class="inline-block px-1.5 py-0.5 rounded bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                            {{ $record->category }}
                        </span>
                    </dd>
                @endif
                {{-- Start date (hidden md+) --}}
                <dd class="text-xs text-gray-400 dark:text-gray-500 md:hidden">
                    {{ $record->start_date }}
                    @if($record->all_day)
                        · {{ __('Sepanjang hari') }}
                    @endif
                </dd>
                {{-- Recurrence (hidden sm+) --}}
                <dd class="text-xs text-gray-400 dark:text-gray-500 sm:hidden">
                    {{ $record->recurrenceSummary }}
                </dd>
            </dl>
        </div>
    </td>

    {{-- Start date (desktop) --}}
    <td class="hidden px-4 py-3.5 md:table-cell">
        <div class="text-sm text-gray-700 dark:text-gray-300 whitespace-nowrap">
            {{ $record->start_date }}
        </div>
        @if($record->end_date)
            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $record->end_date }}</p>
        @endif
    </td>

    {{-- Location (desktop) --}}
    <td class="hidden px-4 py-3.5 lg:table-cell">
        <div class="text-sm text-gray-700 dark:text-gray-300 truncate max-w-xs">
            {{ $record->location ?? '—' }}
        </div>
    </td>

    {{-- Recurrence summary --}}
    <td class="hidden px-4 py-3.5 sm:table-cell">
        <span class="inline-flex items-center max-w-xs px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/20 dark:text-purple-300 border border-purple-100 dark:border-purple-800/40 truncate">
            {{ $record->recurrenceSummary }}
        </span>
    </td>

    {{-- Visibility --}}
    <td class="hidden px-4 py-3.5 sm:table-cell">
        @if($record->is_visible)
            <x-core::badge color="emerald" label="{{ __('Terlihat') }}" />
        @else
            <x-core::badge color="gray" label="{{ __('Tersembunyi') }}" />
        @endif
    </td>

    {{-- Actions --}}
    <td class="px-4 py-3.5 whitespace-nowrap w-px">
        @canany(['bale-agenda.update', 'bale-agenda.delete'])
            <livewire:core.shared-components.item-actions
                :editUrl="route('bale.cms.agendas.edit', $record->id)"
                :deleteId="$record->id"
                :deleteEvent="'deleteAgenda'"
                confirmMessage="{{ __('Yakin ingin menghapus agenda ini?') }}"
                wire:key="item-actions-{{ $record->id }}" />
        @endcanany
    </td>

</tr>
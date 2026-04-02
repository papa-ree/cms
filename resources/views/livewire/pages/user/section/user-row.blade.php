<tr wire:key="user-row-{{ $record->id }}"
    class="hover:bg-gray-50/80 dark:hover:bg-gray-800/50 transition-colors duration-150">
    <td class="px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="h-10 w-10 shrink-0 rounded-full bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-sm shadow-sm ring-1 ring-white/20">
                {{ strtoupper(substr($record->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">{{ $record->name }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 truncate mb-1">{{ $record->email }}</div>

                <dl class="font-normal lg:hidden space-y-0.5">
                    {{-- Role (Hidden SM) --}}
                    <dt class="sr-only sm:hidden">{{ __('Role') }}</dt>
                    <dd class="text-xs sm:hidden">
                        @if($record->role)
                            <span class="px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 dark:bg-indigo-900/40 dark:text-indigo-300 rounded border border-indigo-100 dark:border-indigo-800">
                                {{ $record->role }}
                            </span>
                        @endif
                    </dd>

                    {{-- Username (Hidden MD) --}}
                    <dt class="sr-only md:hidden">{{ __('Username') }}</dt>
                    <dd class="text-[11px] text-gray-500 dark:text-gray-400 md:hidden mt-0.5">
                        <span class="font-medium text-gray-400">@</span>{{ $record->username }}
                    </dd>

                    {{-- Joined (Hidden LG) --}}
                    <dt class="sr-only lg:hidden">{{ __('Joined') }}</dt>
                    <dd class="text-[10px] text-gray-400 dark:text-gray-500 lg:hidden mt-1 italic">
                        {{ __('Joined') }} {{ $record->created_at }}
                    </dd>
                </dl>
            </div>
        </div>
    </td>
    <td class="hidden px-6 py-4 sm:table-cell">
        @if($record->role)
            <span class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-indigo-700 bg-indigo-50 dark:bg-indigo-900/40 dark:text-indigo-300 rounded-md border border-indigo-100 dark:border-indigo-800">
                {{ $record->role }}
            </span>
        @else
            <span class="text-xs text-gray-400 italic">{{ __('No role assigned') }}</span>
        @endif
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 dark:text-gray-400 md:table-cell">
        <span class="font-medium text-gray-400">@</span>{{ $record->username }}
    </td>
    <td class="hidden px-6 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">
        {{ $record->created_at }}
    </td>
</tr>

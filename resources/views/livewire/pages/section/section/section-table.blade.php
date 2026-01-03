<div>
    <x-core::table :links="$this->availableSections" header>

        <x-slot name="thead">
            <tr>
                <th scope="col"
                    class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 dark:text-gray-100">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Page Title
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 lg:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Type
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 md:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            usage
                        </span>
                    </div>
                </th>
                <th scope="col"
                    class="hidden px-3 py-3.5 text-left text-sm font-semibold text-gray-900 dark:text-gray-100 md:table-cell">
                    <div class="flex items-center gap-x-2">
                        <span class="text-xs font-semibold tracking-wide text-gray-800 uppercase dark:text-gray-200">
                            Created
                        </span>
                    </div>
                </th>
                <th scope="col" class="relative py-3.5 pl-3 pr-4">
                    <span class="sr-only">Edit</span>
                </th>
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availableSections as $section)
                <tr wire:key='section-{{ $section->slug }}'
                    class="hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td
                        class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 dark:text-white max-w-0 sm:w-auto sm:max-w-none">
                        <a href="{{ route($section->usage == 'searchable' ? 'bale.cms.sections.edit-searchable' : 'bale.cms.sections.edit', $section->slug) }}"
                            wire:navigate.hover
                            class="block text-sm font-semibold text-gray-800 dark:text-gray-200 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                            {{ $section->name }}
                        </a>
                        <dl class="font-normal lg:hidden">
                            <dt class="sr-only sm:hidden">Created At</dt>
                            <dd class="mt-1 text-gray-500 truncate sm:hidden">
                                <span class="block text-xs text-gray-500">Created At {{ $section->created_at }}</span>
                            </dd>
                        </dl>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 lg:table-cell">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ $section->type }}
                        </span>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 md:table-cell">
                        <span class="block text-sm">{{ $section->usage }}</span>
                    </td>

                    <td class="hidden px-3 py-4 text-sm text-gray-500 dark:text-gray-400 md:table-cell">
                        <span class="block text-sm">{{ $section->created_at->format('d M Y') }}</span>
                    </td>

                    <td class="py-4 pl-3 pr-4 text-sm font-medium text-right ">
                        <x-core::option wire:key="{{ $section->id }}" :item="$section->slug" :itemId="$section->id"
                            route="bale.cms.sections.edit" :deleteButton="$section->type == 'core' ? false : true" />
                    </td>
                </tr>
            @endforeach
        </x-slot>

    </x-core::table>
</div>
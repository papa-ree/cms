<div>
    <x-core::table :links="$this->availablePages" header :activeFilters="array_filter(['Type' => $filterType])">

        <x-slot name="filters">
            <div class="space-y-4">
                <select wire:model.live="filterType"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
                    <option value="">All Types</option>
                    <option value="dynamic">Dynamic</option>
                    <option value="static">Static</option>
                </select>
            </div>
        </x-slot>

        <x-slot name="thead">
            <tr>
                <x-core::table-th
                    label="Page Title"
                    sortBy="title"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    class="hidden lg:table-cell"
                    label="Type"
                    sortBy="type"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    class="hidden lg:table-cell"
                    label="Created At"
                    sortBy="created_at"
                    :sortField="$sortField"
                    :sortDirection="$sortDirection"
                />
                <x-core::table-th
                    label="Action"
                    align="right"
                />
            </tr>
        </x-slot>

        <x-slot name="tbody">
            @foreach ($this->availablePages as $page)
                <tr wire:key='page-{{ $page->slug }}'
                    class="transition-colors duration-300 hover:bg-slate-50 dark:hover:bg-slate-800">
                    {{-- Title --}}
                    <td class="w-full py-4 pl-4 pr-3 text-sm font-medium text-gray-900 max-w-0 sm:w-auto sm:max-w-none">
                        <div class="block py-3 pe-6">
                            <div class="grow">
                                <a href="{{ route('bale.cms.pages.edit', $page->slug) }}"
                                    class="block text-sm text-gray-800 transition ease-in-out dark:text-gray-200 hover:text-emerald-600 dark:hover:text-emerald-400">
                                    {{ $page->title }}
                                </a>
                                <dl class="font-normal lg:hidden">
                                    <dt class="sr-only">Type</dt>
                                    <dd class="text-gray-700 truncate">
                                        <span class="block text-xs text-gray-600 dark:text-gray-200 uppercase tracking-wider">{{ $page->type }}</span>
                                    </dd>
                                    <dt class="sr-only sm:hidden">Created At</dt>
                                    <dd class="text-gray-500 truncate sm:hidden">
                                        <span class="block text-xs text-gray-500">created at {{ $page->created_at }}</span>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </td>

                    {{-- Type --}}
                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        <span class="px-2 py-1 text-xs font-medium rounded-md bg-gray-100 text-gray-800 dark:bg-neutral-700 dark:text-neutral-300 uppercase tracking-wider">
                            {{ $page->type }}
                        </span>
                    </td>

                    {{-- Created At --}}
                    <td class="hidden px-3 py-4 text-sm text-gray-500 lg:table-cell">
                        <span class="block text-sm text-gray-500">{{ $page->created_at }}</span>
                    </td>

                    <td class="size-px whitespace-nowrap text-right">
                        <div class="px-6 py-1.5 inline-block">
                            <livewire:core.shared-components.item-actions
                                :editUrl="route('bale.cms.pages.edit', $page->slug)"
                                :deleteId="$page->id"
                                wire:key="item-actions-{{ $page->id }}"
                                confirmMessage="Yakin ingin menghapus halaman ini?"
                            />
                        </div>
                    </td>
                </tr>
            @endforeach
        </x-slot>

    </x-core::table>
</div>
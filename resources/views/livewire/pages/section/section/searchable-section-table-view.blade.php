<div>
    @php
        $breadcrumbs = [
            ['label' => 'Sections', 'route' => 'bale.cms.sections.index'],
            [
                'label' => $sectionName,
                'route' => 'bale.cms.sections.meta-editor',
                'params' => $sectionSlug
            ]
        ];
    @endphp

    <x-core::breadcrumb :items="$breadcrumbs" :active="'Data Items'" />

    {{-- Header Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);" data-aos="fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                            <x-lucide-database class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold capitalize text-white md:text-4xl">{{ $sectionName }}</h1>
                        </div>
                    </div>
                    <p class="text-white/90 text-lg mb-4">
                        View and manage searchable section data
                    </p>

                    {{-- Search Input --}}
                    <div class="max-w-md">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none">
                                <x-lucide-search class="w-5 h-5 text-white/60" />
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                                placeholder="Search across all fields..."
                                class="w-full pl-12 pr-4 py-3 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 transition-all">
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 justify-center">
                    <a href="{{ route('bale.cms.sections.edit-keys', $sectionSlug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-key class="w-4 h-4 hidden lg:block" />
                        Manage Keys
                    </a>
                    <a href="{{ route('bale.cms.sections.create-searchable-item', $sectionSlug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white text-purple-600 hover:bg-white/90 rounded-lg text-sm font-medium transition-all shadow-lg">
                        <x-lucide-plus class="w-4 h-4 hidden lg:block" />
                        Create Item
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Section --}}
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
        @if(count($this->filteredItems) > 0)
            {{-- Desktop Table View --}}
            <div
                class="hidden md:block overflow-x-auto scrollbar-thin scrollbar-thumb-gray-500 dark:scrollbar-thumb-gray-700 scrollbar-track-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full overscroll-none">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                        <tr>
                            <th
                                class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                #
                            </th>
                            @foreach($availableKeys as $key)
                                @continue(in_array($key, ['id', 'created_at', 'updated_at']))
                                <th
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <x-lucide-tag class="w-4 h-4 text-purple-600" />
                                        {{ $key }}
                                    </div>
                                </th>
                            @endforeach
                            <th
                                class="px-6 py-4 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($this->filteredItems as $index => $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-900/30 transition-colors"
                                wire:key="item-{{ $index }}" data-aos="fade-up" data-aos-delay="{{ $index * 30 }}">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $index + 1 }}
                                </td>
                                @foreach($availableKeys as $key)
                                    @continue(in_array($key, ['id', 'created_at', 'updated_at']))
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if(isset($item[$key]))
                                            @if(is_array($item[$key]))
                                                @if(count($item[$key]) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($item[$key] as $value)
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-md">
                                                                {{ \Illuminate\Support\Str::limit($value, 20) }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-600 italic text-xs">Empty</span>
                                                @endif
                                            @else
                                                {{ $item[$key] }}
                                            @endif
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600 italic text-xs">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('bale.cms.sections.edit-searchable-item', [$sectionSlug, $index]) }}"
                                            wire:navigate.hover
                                            class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors"
                                            title="Edit">
                                            <x-lucide-pencil class="w-4 h-4" />
                                        </a>
                                        <button type="button" wire:click="deleteItem({{ $index }})"
                                            wire:confirm="Are you sure you want to delete this item?"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                                            title="Delete">
                                            <x-lucide-trash-2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile Card View --}}
            <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($this->filteredItems as $index => $item)
                    <div class="p-4" wire:key="item-mobile-{{ $index }}" data-aos="fade-up">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">Item #{{ $index + 1 }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('bale.cms.sections.edit-searchable-item', [$sectionSlug, $index]) }}"
                                    wire:navigate.hover
                                    class="p-2 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                    <x-lucide-pencil class="w-4 h-4" />
                                </a>
                                <button type="button" wire:click="deleteItem({{ $index }})"
                                    wire:confirm="Are you sure you want to delete this item?"
                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                </button>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @foreach($availableKeys as $key)
                                @continue(in_array($key, ['id', 'created_at', 'updated_at']))
                                <div>
                                    <div class="flex items-center gap-1.5 mb-1">
                                        <x-lucide-tag class="w-3 h-3 text-purple-600" />
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">{{ $key }}</span>
                                    </div>
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if(isset($item[$key]))
                                            @if(is_array($item[$key]))
                                                @if(count($item[$key]) > 0)
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach($item[$key] as $value)
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-md">
                                                                {{ $value }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-600 italic text-xs">Empty</span>
                                                @endif
                                            @else
                                                {{ $item[$key] }}
                                            @endif
                                        @else
                                            <span class="text-gray-400 dark:text-gray-600 italic text-xs">-</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Footer Info --}}
            <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                    <div class="flex items-center gap-2">
                        <x-lucide-info class="w-4 h-4" />
                        <span>Showing {{ count($this->filteredItems) }} item(s)</span>
                    </div>
                    @if($searchQuery)
                        <button type="button" wire:click="$set('searchQuery', '')"
                            class="text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium">
                            Clear search
                        </button>
                    @endif
                </div>
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-16">
                <div
                    class="w-20 h-20 mx-auto mb-6 rounded-full bg-linear-to-br from-purple-100 to-purple-200 dark:from-purple-900/20 dark:to-purple-800/20 flex items-center justify-center">
                    @if($searchQuery)
                        <x-lucide-search-x class="w-10 h-10 text-purple-600 dark:text-purple-400" />
                    @else
                        <x-lucide-inbox class="w-10 h-10 text-purple-600 dark:text-purple-400" />
                    @endif
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    @if($searchQuery)
                        No Results Found
                    @else
                        No Items Yet
                    @endif
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    @if($searchQuery)
                        Try adjusting your search query
                    @else
                        Get started by adding items to this section
                    @endif
                </p>
                @if($searchQuery)
                    <button type="button" wire:click="$set('searchQuery', '')"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                        <x-lucide-x class="w-5 h-5" />
                        Clear Search
                    </button>
                @else
                    <a href="{{ route('bale.cms.sections.create-searchable-item', $sectionSlug) }}" wire:navigate.hover
                        class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl transition-colors">
                        <x-lucide-plus class="w-5 h-5" />
                        Add Items
                    </a>
                @endif
            </div>
        @endif
    </div>
</div>
<div>
    {{-- Help Guide --}}
    <div class="mb-6 p-5 bg-linear-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200 dark:border-indigo-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-indigo-600 rounded-xl shadow-lg">
                <x-lucide-move class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Navigation Hierarchy</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Each card shows a parent navigation with its sub-navigation items. Drag items to reorder them within the hierarchy.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-indigo-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Drag the grip icon to reorder items</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-indigo-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Click to add sub-navigation items</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(count($this->availableNavigations) > 0)
        <div class="space-y-6">
            @foreach($this->availableNavigations as $index => $navigation)
                {{-- Parent Navigation Card --}}
                <div class="bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300"
                    data-aos="fade-up" data-aos-delay="{{ $index * 50 }}">
                    
                    {{-- Top Border Indicator --}}
                    <div class="h-1.5 bg-linear-to-r from-purple-500 via-purple-600 to-indigo-600"></div>
                    
                    {{-- Parent Navigation Header --}}
                    <div class="p-6 bg-gradient-to-r from-purple-50/50 to-indigo-50/50 dark:from-purple-900/10 dark:to-indigo-900/10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                {{-- Drag Handle --}}
                                <button class="p-2.5 text-gray-400 hover:text-purple-600 hover:bg-purple-100 dark:hover:bg-purple-900/20 rounded-lg cursor-move transition-all group"
                                    title="Drag to reorder">
                                    <x-lucide-grip-vertical class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                </button>

                                {{-- Icon --}}
                                <div class="p-3 bg-linear-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                    <x-lucide-menu class="w-6 h-6 text-white" />
                                </div>

                                {{-- Content --}}
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1.5">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $navigation->name }}
                                        </h3>
                                        @if($navigation->children_count > 0)
                                            <span class="px-3 py-1 text-xs font-bold text-purple-700 bg-purple-100 rounded-full dark:bg-purple-900/50 dark:text-purple-300 flex items-center gap-1.5">
                                                <x-lucide-git-branch class="w-3 h-3" />
                                                {{ $navigation->children_count }} sub-item{{ $navigation->children_count > 1 ? 's' : '' }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <x-lucide-hash class="w-3.5 h-3.5" />
                                            <code class="font-mono">{{ $navigation->slug }}</code>
                                        </div>
                                        @if($navigation->url_mode)
                                            <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400">
                                                <x-lucide-external-link class="w-3.5 h-3.5" />
                                                <span class="text-xs">External URL</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                                <x-lucide-file class="w-3.5 h-3.5" />
                                                <span class="text-xs">Internal Page</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-2">
                                <a href="{{ route('bale.cms.navigations.edit', $navigation->slug) }}" 
                                    wire:navigate.hover
                                    title="Edit navigation"
                                    class="p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-lg transition-all">
                                    <x-lucide-edit class="w-5 h-5" />
                                </a>
                                <button 
                                    title="Delete navigation"
                                    class="p-2.5 text-gray-600 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                    <x-lucide-trash-2 class="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Sub-Navigation Items --}}
                    @if($navigation->children && count($navigation->children) > 0)
                        <div class="p-6 pt-4">
                            <div class="flex items-center gap-2 mb-4">
                                <x-lucide-corner-down-right class="w-4 h-4 text-indigo-600" />
                                <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Sub-Navigation Items</h4>
                            </div>

                            <div class="space-y-3">
                                @foreach($navigation->children as $childIndex => $child)
                                    <div class="relative group p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all">
                                        {{-- Left Border Indicator --}}
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-linear-to-b from-indigo-500 to-indigo-600 rounded-l-xl opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                {{-- Drag Handle --}}
                                                <button class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded cursor-move transition-all"
                                                    title="Drag to reorder sub-item">
                                                    <x-lucide-grip-vertical class="w-4 h-4" />
                                                </button>

                                                {{-- Sub Item Icon --}}
                                                <div class="p-2 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-sm">
                                                    <x-lucide-corner-down-right class="w-4 h-4 text-white" />
                                                </div>

                                                {{-- Content --}}
                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900 dark:text-white mb-0.5">
                                                        {{ $child->name }}
                                                    </h5>
                                                    <div class="flex items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <div class="flex items-center gap-1">
                                                            <x-lucide-hash class="w-3 h-3" />
                                                            <code class="font-mono">{{ $child->slug }}</code>
                                                        </div>
                                                        @if($child->url_mode)
                                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                                <x-lucide-external-link class="w-3 h-3" />
                                                                URL
                                                            </span>
                                                        @elseif ($child->url_mode === false)
                                                            <span class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                                <x-lucide-file class="w-3 h-3" />
                                                                Page
                                                            </span>
                                                        @elseif ($child->url_mode === null)
                                                            <span class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                                <x-lucide-circle-off class="w-3 h-3" />
                                                                Not Set
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Sub Actions --}}
                                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('bale.cms.navigations.edit', $child->slug) }}" 
                                                    wire:navigate.hover
                                                    class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-indigo-900/20 rounded transition-all">
                                                    <x-lucide-edit class="w-4 h-4" />
                                                </a>
                                                <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 dark:text-gray-400 dark:hover:text-red-400 dark:hover:bg-red-900/20 rounded transition-all">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Add Sub-Navigation Placeholder --}}
                    <div class="px-6 pb-6">
                        <a href="{{ route('bale.cms.navigations.create', ['parent' => $navigation->slug]) }}"
                            class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-gradient-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 dark:hover:from-indigo-900/30 dark:hover:to-purple-900/30 border-2 border-dashed border-indigo-300 dark:border-indigo-700 rounded-xl text-indigo-700 dark:text-indigo-400 font-semibold transition-all hover:shadow-md group">
                            <x-lucide-plus class="w-5 h-5 group-hover:rotate-90 transition-transform" />
                            <span>Add Sub-Navigation to "{{ $navigation->name }}"</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-20 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl">
            <div class="max-w-md mx-auto px-4">
                <div class="relative inline-block mb-6">
                    <div class="w-24 h-24 bg-linear-to-br from-purple-100 to-indigo-200 dark:from-purple-900/20 dark:to-indigo-800/20 rounded-2xl flex items-center justify-center">
                        <x-lucide-menu class="w-12 h-12 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="absolute -top-2 -right-2 p-2 bg-purple-600 rounded-full shadow-lg">
                        <x-lucide-plus class="w-4 h-4 text-white" />
                    </div>
                </div>
                
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">No Navigation Items</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    Create your first navigation item to build your site's menu structure.
                </p>
                
                <a href="{{ route('bale.cms.navigations.create') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <x-lucide-plus class="w-5 h-5" />
                    Create First Navigation
                </a>
            </div>
        </div>
    @endif
</div>
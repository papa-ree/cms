<div x-data="{
    parentSortable: null,
    childSortables: [],
    hasLocalChanges: false,

    markChanged() {
        this.hasLocalChanges = true;
    },



    extractParentOrder() {
        // Use global selector to ensure we find it
        const parentGrid = document.getElementById('parent-grid');
        
        if (!parentGrid) {
            return [];
        }
        
        const parentCards = parentGrid.querySelectorAll('.parent-card');
        
        const ids = Array.from(parentCards).map(card => {
            return card.dataset.id;
        });
        
        return ids;
    },

    extractChildrenData() {
        const parentGrid = document.getElementById('parent-grid');
        if (!parentGrid) return [];

        const childLists = parentGrid.querySelectorAll('.child-list');
        
        const data = [];

        childLists.forEach((list, listIndex) => {
            const parentId = list.dataset.parentId;
            const childItems = list.querySelectorAll('.child-item');
            
            const childIds = Array.from(childItems).map(item => {
                return item.dataset.id;
            });

            if (childIds.length > 0) {
                data.push({
                    parentId: parentId,
                    childIds: childIds
                });
            }
        });

        return data;
    },

    async handleSave() {
        const parentOrder = this.extractParentOrder();
        const childrenData = this.extractChildrenData();

        await $wire.saveAllChanges(parentOrder, childrenData);
        this.hasLocalChanges = false;
    },

    handleCancel() {
        $wire.resetChanges();
        this.hasLocalChanges = false;
        // Refresh Livewire component to restore original state
        $wire.$refresh();
    },

    initSortable() {
        this.$nextTick(() => {
            try {
                // Cleanup scoped to this component
                if (this.parentSortable) {
                    try { this.parentSortable.destroy(); } catch(e) {}
                    this.parentSortable = null;
                }
                this.childSortables.forEach(s => {
                    try { s.destroy(); } catch(e) {}
                });
                this.childSortables = [];

                // Initialize parent cards sortable
                const parentGrid = this.$el.querySelector('#parent-grid');
                if (parentGrid && window.Sortable) {
                    this.parentSortable = new Sortable(parentGrid, {
                        animation: 200,
                        handle: '.parent-handle',
                        draggable: '.parent-card',
                        ghostClass: 'opacity-50',
                        dragClass: 'scale-105',
                        onEnd: () => {
                            // NO Livewire call! Just mark changed locally
                            this.markChanged();
                        }
                    });
                }

                // Initialize children sortable lists
                const childLists = this.$el.querySelectorAll('.child-list');
                childLists.forEach(container => {
                    if (window.Sortable) {
                        const sortable = new Sortable(container, {
                            group: 'shared-children',
                            animation: 200,
                            handle: '.child-handle',
                            draggable: '.child-item',
                            ghostClass: 'bg-indigo-100',
                            dragClass: 'scale-105',
                            onEnd: () => {
                                // NO Livewire call! Just mark changed locally
                                this.markChanged();
                            }
                        });
                        this.childSortables.push(sortable);
                    }
                });
            } catch(e) {
                console.error('SortableJS init error:', e);
            }
        });
    }
}" x-init="initSortable()" @refresh.window="initSortable()">
    {{-- Help Guide --}}
    <div
        class="mb-6 p-5 bg-linear-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 border border-indigo-200 dark:border-indigo-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-indigo-600 rounded-xl shadow-lg">
                <x-lucide-move class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Navigation Hierarchy Manager (Beta Feature)') }}</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Organize your navigation menu with drag-and-drop. Reorder cards or move sub-items between different parents.') }}
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-indigo-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Drag cards to reorder main menus') }}</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-indigo-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Move sub-items within or between cards') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Save Button Section --}}
    <div x-show="hasLocalChanges" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-4"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 p-4 bg-white dark:bg-gray-800 border-2 border-purple-500 dark:border-purple-600 rounded-2xl shadow-2xl backdrop-blur-sm"
        style="display: none;">

        {{-- Indicator Badge --}}
        <div
            class="flex items-center gap-2 px-3 py-1.5 bg-linear-to-r from-purple-100 to-indigo-100 dark:from-purple-900/30 dark:to-indigo-900/30 rounded-lg">
            <div class="w-2 h-2 bg-purple-600 rounded-full animate-pulse"></div>
            <span class="text-sm font-semibold text-purple-700 dark:text-purple-400">{{ __('Unsaved Changes') }}</span>
        </div>

        {{-- Action Buttons --}}
        <div class="flex items-center gap-2">
            {{-- Cancel Button --}}
            <button @click="handleCancel()" :disabled="$wire.isSaving"
                class="px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-xl transition-all disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2">
                <x-lucide-x class="w-4 h-4" />
                {{ __('Cancel') }}
            </button>

            {{-- Save Button --}}
            <button @click="handleSave()" :disabled="$wire.isSaving"
                class="px-5 py-2.5 text-sm font-bold text-white bg-linear-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none flex items-center gap-2">
                <div wire:loading wire:target="saveAllChanges"
                    class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
                <x-lucide-save class="w-4 h-4" wire:loading.remove wire:target="saveAllChanges" />
                <span wire:loading.remove wire:target="saveAllChanges">{{ __('Save Changes') }}</span>
                <span wire:loading wire:target="saveAllChanges">{{ __('Saving...') }}</span>
            </button>
        </div>
    </div>

    @if(count($this->availableNavigations) > 0)
        {{-- Responsive Grid: 3 cols desktop, 2 cols tablet, 1 col mobile --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6" id="parent-grid">
            @foreach($this->availableNavigations as $index => $navigation)
                <div class="parent-card bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 flex flex-col"
                    data-id="{{ $navigation->id }}" wire:key="parent-{{ $navigation->id }}">

                    <div class="h-1.5 bg-linear-to-r from-purple-500 via-purple-600 to-indigo-600"></div>

                    <div
                        class="p-6 bg-linear-to-r from-purple-50/50 to-indigo-50/50 dark:from-purple-900/10 dark:to-indigo-900/10">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 flex-1">
                                <button
                                    class="parent-handle p-2.5 text-gray-400 hover:text-purple-600 hover:bg-purple-100 dark:hover:bg-purple-900/20 rounded-lg cursor-move transition-all group"
                                    title="{{ __('Drag to reorder') }}">
                                    <x-lucide-grip-vertical class="w-5 h-5 group-hover:scale-110 transition-transform" />
                                </button>

                                <div class="p-3 bg-linear-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                    <x-lucide-menu class="w-6 h-6 text-white" />
                                </div>

                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1.5">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                            {{ $navigation->name }}
                                        </h3>
                                        @if($navigation->children_count > 0)
                                            <span
                                                class="px-3 py-1 text-xs font-bold text-purple-700 bg-purple-100 rounded-full dark:bg-purple-900/50 dark:text-purple-300 flex items-center gap-1.5">
                                                <x-lucide-git-branch class="w-3 h-3" />
                                                {{ $navigation->children_count }}
                                                {{ __('sub-item'.($navigation->children_count > 1 ? 's' : '')) }}
                                            </span>
                                        @endif
                                    </div>
                                    <div class="gap-4 text-sm text-gray-600 dark:text-gray-400">
                                        <div class="flex items-center gap-1.5">
                                            <x-lucide-hash class="w-3.5 h-3.5" />
                                            <code class="font-mono">{{ $navigation->slug }}</code>
                                        </div>
                                        @if($navigation->url_mode)
                                            <div class="flex items-center gap-1.5 text-blue-600 dark:text-blue-400">
                                                <x-lucide-external-link class="w-3.5 h-3.5" />
                                                <span class="text-xs">{{ __('External URL') }}</span>
                                            </div>
                                        @elseif ($navigation->url_mode == null)
                                            <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                                <x-lucide-circle-off class="w-3.5 h-3.5" />
                                                <span class="text-xs">{{ __('No URL') }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                                                <x-lucide-file class="w-3.5 h-3.5" />
                                                <span class="text-xs">{{ __('Internal Page') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ route('bale.cms.navigations.edit', $navigation->slug) }}" wire:navigate.hover
                                    title="{{ __('Edit navigation') }}"
                                    class="p-2.5 text-gray-600 hover:text-purple-600 hover:bg-purple-50 dark:text-gray-400 dark:hover:text-purple-400 dark:hover:bg-purple-900/20 rounded-lg transition-all">
                                    <x-lucide-edit class="w-5 h-5" />
                                </a>
                                <livewire:core.shared-components.item-actions :deleteId="$navigation->id"
                                    wire:key="item-actions-{{ $navigation->id }}"
                                    confirmMessage="{{ __('Hapus \':name\' dan semua sub-menu?', ['name' => $navigation->name]) }}" />
                            </div>
                        </div>
                    </div>

                    {{-- Sub-Navigation Items with Sortable --}}
                    <div class="flex-1 p-6 pt-4">
                        <div class="flex items-center gap-2 mb-4">
                            <x-lucide-corner-down-right class="w-4 h-4 text-indigo-600" />
                            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide">
                                {{ __('Sub-Navigation Items') }}</h4>
                        </div>

                        {{-- Stable Container with wire:ignore --}}
                        <div class="relative min-h-[100px]"
                            wire:key="child-wrapper-{{ $navigation->id }}-{{ count($navigation->children) }}">
                            {{-- Placeholder OUTSIDE the draggable list --}}
                            @if(count($navigation->children) == 0)
                                <div
                                    class="absolute inset-0 pointer-events-none border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl flex flex-col items-center justify-center opacity-50 transition-opacity">
                                    <x-lucide-layers class="w-8 h-8 text-gray-300 mb-2" />
                                    <span class="text-sm text-gray-400">{{ __('No sub-items. Drop here!') }}</span>
                                </div>
                            @endif

                            <div class="child-list space-y-3 min-h-[100px] p-1" data-parent-id="{{ $navigation->id }}"
                                wire:ignore>
                                @foreach($navigation->children as $child)
                                    <div class="child-item relative group p-4 bg-white dark:bg-gray-900/50 border-2 border-gray-100 dark:border-gray-700 rounded-xl hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all"
                                        data-id="{{ $child->id }}" wire:key="child-{{ $child->id }}">
                                        <div
                                            class="absolute left-0 top-0 bottom-0 w-1 bg-linear-to-b from-indigo-500 to-indigo-600 rounded-l-xl opacity-0 group-hover:opacity-100 transition-opacity">
                                        </div>

                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3 flex-1">
                                                <button
                                                    class="child-handle p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded cursor-move transition-all"
                                                    title="{{ __('Drag to reorder sub-item') }}">
                                                    <x-lucide-grip-vertical class="w-4 h-4" />
                                                </button>

                                                <div class="p-2 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-sm">
                                                    <x-lucide-corner-down-right class="w-4 h-4 text-white" />
                                                </div>

                                                <div class="flex-1">
                                                    <h5 class="font-semibold text-gray-900 dark:text-white mb-0.5">
                                                        {{ $child->name }}
                                                    </h5>
                                                    <div class="gap-3 text-xs text-gray-500 dark:text-gray-400">
                                                        <div class="flex items-center gap-1">
                                                            <x-lucide-hash class="w-3 h-3" />
                                                            <code class="font-mono">{{ $child->slug }}</code>
                                                        </div>
                                                        @if($child->url_mode)
                                                            <span class="flex items-center gap-1 text-blue-600 dark:text-blue-400">
                                                                <x-lucide-external-link class="w-3 h-3" />
                                                                {{ __('URL') }}
                                                            </span>
                                                        @elseif ($child->url_mode == false)
                                                            <span
                                                                class="flex items-center gap-1 text-emerald-600 dark:text-emerald-400">
                                                                <x-lucide-file class="w-3 h-3" />
                                                                {{ __('Page') }}
                                                            </span>
                                                        @elseif ($child->url_mode === null)
                                                            <span class="flex items-center gap-1 text-gray-600 dark:text-gray-400">
                                                                <x-lucide-circle-off class="w-3 h-3" />
                                                                {{ __('No URL') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div
                                                class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ route('bale.cms.navigations.edit', $child->slug) }}" wire:navigate.hover
                                                    class="p-2 text-gray-600 hover:text-indigo-600 hover:bg-indigo-50 dark:text-gray-400 dark:hover:text-indigo-400 dark:hover:bg-indigo-900/20 rounded transition-all">
                                                    <x-lucide-edit class="w-4 h-4" />
                                                </a>
                                                <livewire:core.shared-components.item-actions :deleteId="$child->id"
                                                    wire:key="item-actions-{{ $child->id }}"
                                                    confirmMessage="{{ __('Hapus \':name\'?', ['name' => $child->name]) }}" />
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <a href="{{ route('bale.cms.navigations.create', ['parent' => $navigation->slug]) }}"
                            wire:navigate.hover
                            class="flex items-center justify-center gap-2 w-full py-3 px-4 bg-linear-to-r from-indigo-50 to-purple-50 hover:from-indigo-100 hover:to-purple-100 dark:from-indigo-900/20 dark:to-purple-900/20 dark:hover:from-indigo-900/30 dark:hover:to-purple-900/30 border-2 border-dashed border-indigo-300 dark:border-indigo-700 rounded-xl text-indigo-700 dark:text-indigo-400 font-semibold transition-all hover:shadow-md group">
                            <x-lucide-plus class="w-5 h-5 group-hover:rotate-90 transition-transform" />
                            <span>{{ __('Add Sub-Navigation to ":name"', ['name' => $navigation->name]) }}</span>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div
            class="text-center py-20 bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl">
            <div class="max-w-md mx-auto px-4">
                <div class="relative inline-block mb-6">
                    <div
                        class="w-24 h-24 bg-linear-to-br from-purple-100 to-indigo-200 dark:from-purple-900/20 dark:to-indigo-800/20 rounded-2xl flex items-center justify-center">
                        <x-lucide-menu class="w-12 h-12 text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="absolute -top-2 -right-2 p-2 bg-purple-600 rounded-full shadow-lg">
                        <x-lucide-plus class="w-4 h-4 text-white" />
                    </div>
                </div>

                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ __('No Navigation Items') }}</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8 leading-relaxed">
                    {{ __('Create your first navigation item to build your site\'s menu structure.') }}
                </p>

                <a href="{{ route('bale.cms.navigations.create', 'new') }}"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-linear-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all">
                    <x-lucide-plus class="w-5 h-5" />
                    {{ __('Create First Navigation') }}
                </a>
            </div>
        </div>
    @endif

</div>
<div x-data="{ 
    childSortable: null,

    initChildSort() {
        this.$nextTick(() => {
            // Clean up existing instance if any
            if (this.childSortable) {
                this.childSortable.destroy();
                this.childSortable = null;
            }

            const childList = document.getElementById('child-list');
            if (childList && window.Sortable) {
                this.childSortable = new Sortable(childList, {
                    animation: 200,
                    handle: '.child-handle',
                    draggable: '.child-item',
                    ghostClass: 'bg-purple-100',
                    dragClass: 'scale-105',
                    onEnd: (evt) => {
                        const ids = Array.from(childList.querySelectorAll('.child-item')).map(item => item.dataset.id);
                        this.$wire.reorderChildren(ids);
                    }
                });
            }
        });
    }
}" x-init="initChildSort()" @navigation-reordered.window="initChildSort()">
    {{-- Breadcrumb Navigation --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 text-sm">
            <a href="{{ route('bale.cms.navigations.index') }}" wire:navigate.hover
                class="inline-flex items-center gap-1.5 px-3 py-1.5 text-gray-600 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-600 transition-all">
                <span>Navigations</span>
            </a>

            @if($parent)
                <x-lucide-chevron-right class="w-4 h-4 text-gray-400" />
                <a href="{{ route('bale.cms.navigations.edit', $parent->slug) }}" wire:navigate.hover
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-gray-600 hover:text-purple-600 dark:text-gray-400 dark:hover:text-purple-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:border-purple-300 dark:hover:border-purple-600 transition-all">
                    <x-lucide-menu class="w-3.5 h-3.5" />
                    <span>{{ $parent->name }}</span>
                </a>
            @endif

            <x-lucide-chevron-right class="w-4 h-4 text-gray-400" />
            <span class="px-3 py-1.5 text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg font-medium">
                Edit: {{ $name }}
            </span>
        </div>
    </div>

    {{-- Hero Section --}}
    <div class="relative overflow-hidden p-6 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>

        <div class="relative z-10">
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-white/20 backdrop-blur-md rounded-lg">
                    <x-lucide-menu class="w-6 h-6 text-white" />
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Navigation</h1>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        {{-- Edit Form --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Navigation Details</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Configure your navigation item settings</p>
            </div>

            <form wire:submit="update(Object.fromEntries(new FormData($event.target)))"
                x-data="{ selectUrl: $wire.entangle('url_mode'), navigationTitle: $wire.entangle('name'), navigationSlug: $wire.entangle('slug') }"
                x-cloak class="space-y-5">

                <div>
                    <x-core::input label="Navigation Name" x-model="navigationTitle" wire:model='name' />
                    <x-core::input-error for="name" class="mt-2" />
                </div>

                <div>
                    <x-core::input label="Navigation Slug" wire:model='slug' name="slug" x-slug="navigationTitle"
                        x-model="navigationSlug" />
                    <x-core::input-error for="slug" class="mt-2" />
                </div>

                <div>
                    <x-core::label value="Navigation Mode" class="mb-3" />
                    <div
                        class="p-4 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl">
                        <div class="grid gap-3 sm:grid-cols-2">
                            <label for="url_mode" @click="selectUrl=true"
                                class="flex items-center justify-between p-4 text-sm bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-purple-300 dark:hover:border-purple-600 transition-all"
                                :class="selectUrl ? 'border-purple-500 dark:border-purple-500 ring-2 ring-purple-200 dark:ring-purple-900' : ''">
                                <div class="flex items-center gap-3">
                                    <x-lucide-link class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                    <span class="font-medium text-gray-900 dark:text-white">Use URL</span>
                                </div>
                                <input type="radio" name="url_mode" wire:model='url_mode' value="1"
                                    class="w-4 h-4 text-purple-600 focus:ring-purple-500" id="url_mode">
                            </label>

                            <label for="page_mode" @click="selectUrl=false"
                                class="flex items-center justify-between p-4 text-sm bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:border-purple-300 dark:hover:border-purple-600 transition-all"
                                :class="!selectUrl ? 'border-purple-500 dark:border-purple-500 ring-2 ring-purple-200 dark:ring-purple-900' : ''">
                                <div class="flex items-center gap-3">
                                    <x-lucide-file class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                    <span class="font-medium text-gray-900 dark:text-white">Select Page</span>
                                </div>
                                <input type="radio" name="url_mode" wire:model='url_mode' value="0"
                                    class="w-4 h-4 text-purple-600 focus:ring-purple-500" id="page_mode">
                            </label>
                        </div>

                        <x-core::input-error for="url_mode" class="mt-2" />

                        <div class="mt-4" x-show="selectUrl==true" x-transition>
                            <x-core::input useInlineAddon="true" wire:model="url" addon="https://" />
                            <x-core::input-error for="url" class="mt-2" />
                        </div>

                        <div class="mt-4" x-show="selectUrl==false" x-transition>
                            <x-core::select-dropdown label="Select Page" :items="$this->availablePages"
                                valueField="slug" labelField="title" model="page_slug" />
                            <x-core::input-error for="page_slug" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <x-core::button type="submit" label="Update Navigation" class="gap-x-2">
                        <x-slot name="icon">
                            <x-lucide-save class="w-4 h-4" />
                        </x-slot>
                    </x-core::button>
                </div>
            </form>
        </div>

        {{-- Sub Navigation --}}
        @if (!$parent)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Sub Navigation</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Drag to reorder child items</p>
                    </div>
                    <div class="p-2.5 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-md">
                        <x-lucide-list class="w-5 h-5 text-white" />
                    </div>
                </div>

                @if(count($this->availableChilds) > 0)
                    <div class="space-y-3 mb-6" id="child-list">
                        @foreach($this->availableChilds as $child)
                            <div class="child-item group p-4 bg-gray-50 dark:bg-gray-900/50 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-indigo-300 dark:hover:border-indigo-600 transition-all"
                                data-id="{{ $child->id }}" wire:key="{{ $child->slug }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3 flex-1">
                                        <button class="child-handle p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded cursor-move transition-all"
                                            title="Drag to reorder">
                                            <x-lucide-grip-vertical class="w-4 h-4" />
                                        </button>
                                        <x-lucide-corner-down-right class="w-4 h-4 text-indigo-600" />
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">{{ $child->name }}</h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 font-mono">{{ $child->slug }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('bale.cms.navigations.edit', $child->slug) }}" wire:navigate.hover
                                        class="p-2 text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 opacity-0 group-hover:opacity-100 transition-all">
                                        <x-lucide-edit class="w-4 h-4" />
                                    </a>

                                    <livewire:core.shared-components.item-actions 
                                    class="opacity-0 group-hover:opacity-100 transition-all"
                                            :deleteId="$child->id"
                                            confirmMessage="Hapus '{{ $child->name }}'?"
                                        />
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 mb-6">
                        <div
                            class="w-12 h-12 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <x-lucide-inbox class="w-6 h-6 text-gray-400" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No sub-navigation items yet</p>
                    </div>
                @endif

                <x-core::button type="button" link :href="route('bale.cms.navigations.create', $slug)"
                    label="Add Sub Navigation" class="w-full gap-x-2">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-4 h-4" />
                    </x-slot>
                </x-core::button>
            </div>
        @endif
    </div>
</div>
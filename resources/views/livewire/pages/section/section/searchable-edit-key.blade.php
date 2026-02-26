<div x-data="{
    keys: @js($availableKeys),
    newKey: '',
    sortingEnabled: false,
    sortableInstance: null,
    addKey() {
        if (this.newKey.trim() !== '' && !this.keys.includes(this.newKey.trim())) {
            this.keys.push(this.newKey.trim());
            this.newKey = '';
        }
    },
    removeKey(index) {
        if (confirm('Are you sure? This will remove this key from all items.')) {
            this.keys.splice(index, 1);
        }
    },
    initSortable() {
        let el = document.getElementById('keys-list');
        if (el && window.Sortable) {
            this.sortableInstance = window.Sortable.create(el, {
                animation: 150,
                ghostClass: 'bg-purple-100',
                handle: '.cursor-move',
                disabled: !this.sortingEnabled,
                onEnd: (evt) => {
                    let item = this.keys[evt.oldIndex];
                    this.keys.splice(evt.oldIndex, 1);
                    this.keys.splice(evt.newIndex, 0, item);
                }
            });

            this.$watch('sortingEnabled', value => {
                this.sortableInstance.option('disabled', !value);
            });
        } else if (!window.Sortable) {
            console.error('SortableJS not found. Please ensure it is loaded.');
        }
    }
}" x-init="initSortable()">
    @php
        $breadcrumbs = [
            ['label' => 'Sections', 'route' => 'bale.cms.sections.index'],
            [
                'label' => $name,
                'route' => 'bale.cms.sections.view-searchable',
                'params' => $slug
            ]
        ];
    @endphp

    <x-core::breadcrumb :items="$breadcrumbs" :active="'Edit Keys'" />

    {{-- Header Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                            <x-lucide-key class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white md:text-4xl">Edit Field Keys</h1>
                        </div>
                    </div>
                    <p class="text-white/90 text-lg mb-2">
                        Manage searchable field keys for: <span class="font-semibold">{{ $name }}</span>
                    </p>
                    <p class="text-white/80 text-sm">
                        Define the structure of your searchable data collection
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 justify-center">
                    <a href="{{ route('bale.cms.sections.view-searchable', $slug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-arrow-left class="w-4 h-4 hidden lg:block" />
                        Back to Data Items
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Help Guide --}}
    <div
        class="mb-8 p-5 bg-linear-to-r from-purple-50 to-pink-50 dark:from-purple-900/20 dark:to-pink-900/20 border border-purple-200 dark:border-purple-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-purple-600 rounded-xl shadow-lg">
                <x-lucide-info class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">About Field Keys</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Keys define the structure of your searchable items. Each item will have fields based on these keys.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-purple-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Add keys before creating items</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-purple-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Removing keys will clear related
                            data</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-purple-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Use key named images, files, attachments, documents, photos, gallery
                            for file upload field type
                        </span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-purple-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Use suffix for file upload field type
                            example: <span class="font-semibold">_image, _file, _foto, _doc, _pdf, _photo,
                                _attachment, _gambar</span>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Key Management Card --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="p-2.5 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg shadow-md">
                <x-lucide-tags class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Field Keys Management</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Add or remove field keys</p>
            </div>
        </div>

        {{-- Add New Key --}}
        <div class="mb-6">
            <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Add New Field Key</label>
            <div class="flex gap-2">
                <div class="flex-1">
                    <x-core::input x-model="newKey" @keydown.enter.prevent="addKey"
                        placeholder="e.g. product_name, price, category" />
                </div>
                <button type="button" @click="addKey"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition-all">
                    <x-lucide-plus class="w-4 h-4" />
                    Add Key
                </button>
            </div>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Press Enter or click Add Key to create</p>
        </div>

        {{-- Keys List --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2">
                    <x-lucide-list class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Active Keys (<span x-text="keys.length"></span>)
                    </span>
                </div>

                {{-- Sortable Toggle --}}
                {{-- <div class="flex items-center gap-2">
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Enable Reordering</span>
                    <input type="checkbox" x-model="sortingEnabled" id="sortable-toggle"
                        class="relative w-13 h-7 bg-gray-100 checked:bg-none checked:bg-purple-600 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 ring-1 ring-transparent focus:border-purple-600 focus:ring-purple-600 appearance-none before:inline-block before:w-6 before:h-6 before:bg-white before:translate-x-0 checked:before:translate-x-full before:shadow before:rounded-full before:transform before:ring-0 before:transition before:ease-in-out before:duration-200 dark:bg-gray-700 dark:before:bg-gray-400 dark:checked:before:bg-purple-200 dark:focus:before:ring-gray-600">
                </div> --}}
            </div>

            <div id="keys-list"
                class="flex flex-wrap gap-2 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 min-h-[100px]">
                <template x-for="(key, index) in keys" :key="key">
                    <div :data-id="key"
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-purple-200 dark:border-purple-800 rounded-lg shadow-sm group">
                        <x-lucide-grip-vertical x-show="sortingEnabled"
                            class="w-3.5 h-3.5 text-gray-400 group-hover:text-purple-600 cursor-move" />
                        <x-lucide-tag class="w-3.5 h-3.5 text-purple-600" />
                        <span class="text-sm font-medium text-gray-800 dark:text-white" x-text="key"></span>
                        <button type="button" @click="removeKey(index)"
                            class="p-0.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors ml-1">
                            <x-lucide-x class="w-3.5 h-3.5" />
                        </button>
                    </div>
                </template>
            </div>

            <div x-show="keys.length === 0"
                class="mt-2 p-8 bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-center">
                <x-lucide-inbox class="w-10 h-10 text-gray-400 mx-auto mb-2" />
                <p class="text-sm text-gray-600 dark:text-gray-400">No keys yet. Add your first key above.</p>
            </div>
        </div>
    </div>

    {{-- Save Section --}}
    <div
        class="mt-6 flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <x-lucide-alert-circle class="w-5 h-5 text-purple-600" />
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Ready to save changes?</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">This will update the section structure</p>
            </div>
        </div>
        <button type="button" @click="$wire.save(keys)"
            class="inline-flex items-center gap-2 px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition-all">
            <x-lucide-save class="w-4 h-4" />
            Save Keys
        </button>
    </div>

    {{--
    <script>
        document.addEventListener( 'livewire:navigated', () =>
        {
            initSortable();
        } );

        // Initialize on first load as well if not strictly SPA
        initSortable();

        function initSortable ()
        {
            var el = document.getElementById( 'keys-list' );
            if ( el ) {
                var sortable = Sortable.create( el, {
                    animation: 150,
                    ghostClass: 'bg-purple-100',
                    onEnd: function ( evt )
                    {
                        var orderedKeys = sortable.toArray();
                        @this.updateOrder( orderedKeys );
                    }
                } );
            }
        }
    </script> --}}
</div>
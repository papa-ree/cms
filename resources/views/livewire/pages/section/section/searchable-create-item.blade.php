<div x-data="{
    item: @js($currentItem),
    tempInputs: {},
    addValue(key) {
        let val = this.tempInputs[key] || '';
        if (typeof val === 'string' && val.trim() === '') return;
        if (!this.item[key]) this.item[key] = [];
        this.item[key].push(val);
        this.tempInputs[key] = '';
    },
    removeValue(key, index) {
        if (confirm('Are you sure you want to remove this value?')) {
            this.item[key].splice(index, 1);
        }
    },
    updateValue(key, index, newValue) {
        this.item[key][index] = newValue;
    }
}">
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

    <x-core::breadcrumb :items="$breadcrumbs" :active="$editMode ? 'Edit Item' : 'Create Item'" />

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
                            <x-lucide-file-plus class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white md:text-4xl">
                                {{ $editMode ? 'Edit Item' : 'Create New Item' }}
                            </h1>
                        </div>
                    </div>
                    <p class="text-white/90 text-lg mb-2">
                        {{ $editMode ? 'Update' : 'Add new data to' }}: <span class="font-semibold">{{ $name }}</span>
                    </p>
                    <p class="text-white/80 text-sm">
                        Fill in the fields below based on your defined keys
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 justify-center">
                    <a href="{{ route('bale.cms.sections.view-searchable', $slug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-arrow-left class="w-4 h-4 hidden lg:block" />
                        Back to Table
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Help Guide --}}
    <div
        class="mb-8 p-5 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-600 rounded-xl shadow-lg">
                <x-lucide-lightbulb class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">How to Use</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Each field can have multiple values. Add values one by one using the input and Add button.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Press Enter or click Add to add
                            values</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Click X on tags to remove values</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Item Form --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="p-2.5 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-md">
                <x-lucide-edit class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Item Data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $editMode ? 'Modify existing values' : 'Enter values for each field' }}
                </p>
            </div>
        </div>

        <div class="space-y-5">
            @foreach ($availableKeys as $key)
                @continue(in_array($key, ['id', 'created_at', 'updated_at']))
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700">
                    {{-- Key Label --}}
                    <div class="flex items-center gap-1.5 mb-3">
                        <x-lucide-tag class="w-4 h-4 text-blue-600" />
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</label>
                    </div>

                    {{-- Input + Add Button --}}
                    <div class="mb-3">
                        <div class="flex gap-2">
                            <div class="flex-1">
                                @if ($key == 'date')
                                    <x-core::input type="date" x-model="tempInputs['{{ $key }}']"
                                        @keydown.enter="addValue('{{ $key }}')" placeholder="Select date" />
                                @else
                                    <x-core::input x-model="tempInputs['{{ $key }}']" @keydown.enter="addValue('{{ $key }}')"
                                        placeholder="Enter {{ $key }}" />
                                @endif
                            </div>
                            <button type="button" @click="addValue('{{ $key }}')"
                                class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                <x-lucide-plus class="w-4 h-4" />
                                Add
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Press Enter or click Add to add value
                        </p>
                    </div>

                    {{-- Display Values as Tags --}}
                    <div x-show="item['{{ $key }}'] && item['{{ $key }}'].length > 0">
                        <div class="flex items-center gap-2 mb-2">
                            <x-lucide-list class="w-3.5 h-3.5 text-gray-600 dark:text-gray-400" />
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                Values (<span x-text="item['{{ $key }}'] ? item['{{ $key }}'].length : 0"></span>)
                            </span>
                        </div>
                        <div
                            class="flex flex-wrap gap-2 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 max-h-48 overflow-y-auto">
                            <template x-for="(value, vIndex) in item['{{ $key }}']" :key="vIndex">
                                <div class="hs-dropdown relative inline-flex [--auto-close:inside]">
                                    <button :id="'dropdown-{{ $key }}-' + vIndex" type="button"
                                        class="hs-dropdown-toggle inline-flex items-center gap-x-2 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 bg-blue-50 text-gray-800 shadow-sm hover:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-blue-900/30 dark:border-blue-800 dark:text-white dark:hover:bg-blue-900/50">
                                        <span x-text="value"></span>
                                        <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="m6 9 6 6 6-6" />
                                        </svg>
                                    </button>

                                    <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[300px] z-50 bg-white shadow-xl rounded-xl p-4 border border-gray-100 dark:bg-gray-800 dark:border-gray-700"
                                        :aria-labelledby="'dropdown-{{ $key }}-' + vIndex">
                                        <div x-data="{ editedValue: value }" x-init="$watch('value', v => editedValue = v)">
                                            <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-200">
                                                Edit Value
                                            </label>
                                            <textarea x-model="editedValue" @keydown.stop
                                                class="py-3 px-4 block w-full bg-gray-50 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                rows="4" placeholder="Enter value..."></textarea>

                                            <div
                                                class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                <button type="button" @click="removeValue('{{ $key }}', vIndex)"
                                                    class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:bg-red-900/20">
                                                    <x-lucide-trash-2 class="w-4 h-4" />
                                                    Remove
                                                </button>
                                                <button type="button"
                                                    @click="updateValue('{{ $key }}', vIndex, editedValue); $el.closest('.hs-dropdown').classList.remove('open');"
                                                    class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                    <x-lucide-check class="w-4 h-4" />
                                                    Update
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                    <div x-show="!item['{{ $key }}'] || item['{{ $key }}'].length === 0"
                        class="p-3 bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-center">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            No values yet. Add your first value above.
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Save Section --}}
    <div
        class="mt-6 flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <x-lucide-info class="w-5 h-5 text-blue-600" />
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Ready to save?</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ $editMode ? 'This will update the existing item' : 'This will add a new item to the collection' }}
                </p>
            </div>
        </div>
        <button type="button" @click="$wire.save(item)"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all">
            <x-lucide-save class="w-4 h-4" />
            {{ $editMode ? 'Update Item' : 'Create Item' }}
        </button>
    </div>

    <script>
        document.addEventListener( 'livewire:navigated', () =>
        {
            window.HSStaticMethods.autoInit();
        } );
    </script>
</div>
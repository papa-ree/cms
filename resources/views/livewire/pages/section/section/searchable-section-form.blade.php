<div>

    @php
        $breadcrumbs = [
            ['label' => 'Sections', 'route' => 'bale.cms.sections.index']
        ];
        if ($editMode) {
            $breadcrumbs[] = [
                'label' => $name,
                'route' => 'bale.cms.sections.edit',
                'params' => $slug,
                'icon' => 'menu'
            ];
        }
    @endphp

    <x-core::breadcrumb :items="$breadcrumbs" :active="'Edit: ' . $name" />

    {{-- View Data Button --}}
    @if($editMode)
        <div class="mb-6">
            <a href="{{ route('bale.cms.sections.view-searchable', $slug) }}" wire:navigate.hover
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">
                <x-lucide-table class="w-5 h-5" />
                View Data Table
            </a>
        </div>
    @endif

    {{-- Help Guide --}}
    <div
        class="mb-8 p-5 bg-linear-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-emerald-600 rounded-xl shadow-lg">
                <x-lucide-search class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Searchable Section Guide</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Create searchable data collections with custom fields. Define your keys first, then add items with
                    values for each key.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-emerald-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Perfect for lists, directories, and
                            catalogs</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-emerald-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Auto-generated searchable database</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="searchableForm"
        @submit.prevent="$wire.call(@if(!$editMode) 'save' @else 'update' @endif, Object.fromEntries(new FormData($event.target)))"
        class="space-y-6">

        <div x-data="{ sectionName: $wire.entangle('name'), sectionSlug: $wire.entangle('slug') }">
            {{-- Section Info & Key Management --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {{-- LEFT: Section Basic Info --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg shadow-md">
                            <x-lucide-layers class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Section Information</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Basic section details</p>
                        </div>
                    </div>

                    <div class="space-y-5">
                        {{-- NAME --}}
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-type class="w-4 h-4 text-gray-400" />
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Section Name</label>
                            </div>
                            <x-core::input wire:model="name" x-model="sectionName" placeholder="e.g. Product Catalog" />
                            <x-core::input-error for="name" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Display name for this collection
                            </p>
                        </div>

                        {{-- SLUG --}}
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-hash class="w-4 h-4 text-gray-400" />
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">URL Slug</label>
                            </div>
                            <x-core::input wire:model="slug" name="slug" x-slug="sectionName" x-model="sectionSlug"
                                placeholder="product-catalog" />
                            <x-core::input-error for="slug" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated from name</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT: Key Management --}}
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md">
                            <x-lucide-key class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Field Keys</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Define data structure</p>
                        </div>
                    </div>

                    {{-- Add New Key --}}
                    <div class="mb-4">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 block">Add New Field
                            Key</label>
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <x-core::input wire:model="newKey" @keydown.enter='$wire.addKey'
                                    placeholder="e.g. product_name, price, category" />
                            </div>
                            <button type="button" wire:click="addKey"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                <x-lucide-plus class="w-4 h-4" />
                                Add
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Press Enter or click Add to create key
                        </p>
                    </div>

                    {{-- Keys List --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-tags class="w-4 h-4 text-gray-600 dark:text-gray-400" />
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Active Keys ({{ count($availableKeys) }})
                            </span>
                        </div>

                        @if(count($availableKeys) > 0)
                            <div
                                class="flex flex-wrap gap-2 p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700 max-h-48 overflow-y-auto">
                                @foreach ($availableKeys as $i => $key)
                                    <div
                                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-emerald-200 dark:border-emerald-800 rounded-lg shadow-sm">
                                        <x-lucide-tag class="w-3.5 h-3.5 text-emerald-600" />
                                        <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $key }}</span>
                                        <button type="button" wire:click="removeKey({{ $i }})"
                                            class="p-0.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                            <x-lucide-x class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div
                                class="p-6 bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-center">
                                <x-lucide-inbox class="w-8 h-8 text-gray-400 mx-auto mb-2" />
                                <p class="text-sm text-gray-600 dark:text-gray-400">No keys yet. Add your first key above.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Items Section --}}
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-md">
                            <x-lucide-database class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Data Items</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Add content for each field</p>
                        </div>
                    </div>

                    <button type="button" wire:click="addItem"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">
                        <x-lucide-plus class="w-4 h-4" />
                        Add Item
                    </button>
                </div>

                <div class="space-y-4">
                    @forelse ($items as $i => $item)
                        <div class="relative border-2 border-blue-200 dark:border-blue-800 rounded-xl overflow-hidden"
                            data-aos="fade-up" data-aos-delay="{{ $i * 50 }}">
                            {{-- Item Indicator --}}
                            <div class="absolute top-0 left-0 w-2 h-full bg-linear-to-b from-blue-500 to-blue-600"></div>

                            <div class="p-5 pl-7 bg-blue-50/30 dark:bg-blue-900/10">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-blue-600 rounded-lg">
                                            <x-lucide-file-text class="w-4 h-4 text-white" />
                                        </div>
                                        <h4 class="font-bold text-gray-900 dark:text-white">Item #{{ $i + 1 }}</h4>
                                    </div>

                                    <button type="button" wire:click="removeItem({{ $i }})"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                        <span class="text-sm font-medium">Remove</span>
                                    </button>
                                </div>

                                {{-- Dynamic Fields --}}
                                @if(count($availableKeys) > 0)
                                    <div class="space-y-5">
                                        @foreach ($availableKeys as $key)
                                            <div class="p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
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
                                                                <x-core::input 
                                                                    type="date" 
                                                                    wire:model="tempInputs.{{ $i }}.{{ $key }}"
                                                                    @keydown.enter="$wire.addValue({{ $i }}, '{{ $key }}')"
                                                                    placeholder="Select date" />
                                                            @else
                                                                <x-core::input 
                                                                    wire:model="tempInputs.{{ $i }}.{{ $key }}"
                                                                    @keydown.enter="$wire.addValue({{ $i }}, '{{ $key }}')"
                                                                    placeholder="Enter {{ $key }}" />
                                                            @endif
                                                        </div>
                                                        <button 
                                                            type="button" 
                                                            wire:click="addValue({{ $i }}, '{{ $key }}')"
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
                                                @if(isset($items[$i][$key]) && is_array($items[$i][$key]) && count($items[$i][$key]) > 0)
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <x-lucide-list class="w-3.5 h-3.5 text-gray-600 dark:text-gray-400" />
                                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                                                Values ({{ count($items[$i][$key]) }})
                                                            </span>
                                                        </div>
                                                        <div class="flex flex-wrap gap-2 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 max-h-32 overflow-y-auto">
                                                            @foreach ($items[$i][$key] as $vIndex => $value)
                                                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border border-blue-200 dark:border-blue-800 rounded-lg shadow-sm">
                                                                    <span class="text-sm font-medium text-gray-800 dark:text-white">{{ $value }}</span>
                                                                    <button 
                                                                        type="button" 
                                                                        wire:click="removeValue({{ $i }}, '{{ $key }}', {{ $vIndex }})"
                                                                        class="p-0.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                                                        <x-lucide-x class="w-3.5 h-3.5" />
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="p-3 bg-gray-50 dark:bg-gray-900/50 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-center">
                                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                                            No values yet. Add your first value above.
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div
                                        class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                        <div class="flex items-start gap-2">
                                            <x-lucide-alert-triangle class="w-5 h-5 text-yellow-600 mt-0.5" />
                                            <div>
                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200">No Keys
                                                    Defined</p>
                                                <p class="text-xs text-yellow-700 dark:text-yellow-300 mt-1">Please add keys in
                                                    the "Field Keys" section above first.</p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div
                            class="text-center py-16 bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                            <div
                                class="w-20 h-20 mx-auto mb-4 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                                <x-lucide-inbox class="w-10 h-10 text-blue-600 dark:text-blue-400" />
                            </div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Items Yet</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                @if(count($availableKeys) > 0)
                                    Start adding items to your searchable collection
                                @else
                                    Define your field keys first, then add items
                                @endif
                            </p>
                            @if(count($availableKeys) > 0)
                                <button type="button" wire:click="addItem"
                                    class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-lg transition-all">
                                    <x-lucide-plus class="w-5 h-5" />
                                    Add First Item
                                </button>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Submit Section --}}
            <div
                class="flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <x-lucide-info class="w-5 h-5 text-blue-600" />
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Ready to save?</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Make sure all required fields are filled</p>
                    </div>
                </div>
                <x-core::button label="Save Section" type="submit" class="gap-x-2">
                    <x-slot name="icon">
                        <x-lucide-save class="w-4 h-4" />
                    </x-slot>
                </x-core::button>
            </div>
        </div>
    </form>

    <script>
        document.getElementById( 'searchableForm' ).addEventListener( 'keydown', function ( event )
        {
            if ( event.keyCode === 13 || event.key === 'Enter' ) {
                event.preventDefault();
            }
        } );
    </script>
</div>
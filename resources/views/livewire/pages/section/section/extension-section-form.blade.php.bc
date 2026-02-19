<div>
    <x-core::breadcrumb :items="[['label' => 'Sections', 'route' => 'bale.cms.sections.index']]" active="Create New Section" />

    {{-- Help Guide --}}
    <div class="mb-8 p-5 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-600 rounded-xl shadow-lg">
                <x-lucide-info class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Extension Section Guide</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Create custom sections with flexible JSON structure. You can add multiple keys with different data types and nest them up to 3 levels deep.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Support for string, number, boolean, and file types</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Nested objects for complex data structures</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form x-data="{ 
        sectionName: $wire.entangle('name'), 
        sectionSlug: $wire.entangle('slug'),
        actived: $wire.entangle('actived'),
        }"
        @submit.prevent="$wire.call(@if(!$editMode) 'save' @else 'update' @endif, Object.fromEntries(new FormData($event.target)))"
        class="space-y-6">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- LEFT SIDEBAR - Section Info --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 sticky top-6 space-y-5">
                    <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-linear-to-br from-purple-500 to-purple-600 rounded-lg shadow-md">
                            <x-lucide-box class="w-5 h-5 text-white" />
                        </div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                            @if(!$editMode) New @else Edit @endif Section
                        </h3>
                    </div>

                    {{-- NAME --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <x-lucide-type class="w-4 h-4 text-gray-400" />
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Section Name</label>
                        </div>
                        <x-core::input wire:model="name" x-model="sectionName" placeholder="e.g. Featured Products" />
                        <x-core::input-error for="name" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Display name for this section</p>
                    </div>

                    {{-- SLUG --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <x-lucide-hash class="w-4 h-4 text-gray-400" />
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">URL Slug</label>
                        </div>
                        <x-core::input wire:model="slug" name="slug" x-slug="sectionName" x-model="sectionSlug"
                            placeholder="featured-products" />
                        <x-core::input-error for="slug" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Auto-generated from name</p>
                    </div>

                    {{-- ACTIVE TOGGLE --}}
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                            <div class="flex items-center gap-3">
                                <x-lucide-toggle-left class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                                <div>
                                    <label for="bale-show-extension-section" class="text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                        Enable Section
                                    </label>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Show on website</p>
                                </div>
                            </div>
                            <label for="bale-show-extension-section" class="relative inline-block w-12 h-6 cursor-pointer">
                                <input type="checkbox" id="bale-show-extension-section" :checked="actived"
                                    wire:model='actived' class="peer sr-only">
                                <span class="absolute inset-0 bg-gray-300 rounded-full transition peer-checked:bg-emerald-600 dark:bg-gray-700 dark:peer-checked:bg-emerald-500"></span>
                                <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition peer-checked:translate-x-6"></span>
                            </label>
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="space-y-2 pt-4">
                        @if ($slug)
                            <x-core::button label="Data Items" link :href="route('bale.cms.sections.view-searchable', $slug)"
                                class="w-full justify-center gap-x-2" type="button">
                                <x-slot name="icon">
                                    <x-lucide-database class="w-4 h-4" />
                                </x-slot>
                            </x-core::button>
                        @endif

                        <x-core::button label="Save Section" class="w-full justify-center gap-x-2" type="submit">
                            <x-slot name="icon">
                                <x-lucide-save class="w-4 h-4" />
                            </x-slot>
                        </x-core::button>
                    </div>
                </div>
            </div>

            {{-- RIGHT CONTENT - JSON Builder --}}
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md">
                                <x-lucide-braces class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Content Structure</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Define JSON data fields</p>
                            </div>
                        </div>

                        <button type="button" wire:click="addKey"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Field
                        </button>
                    </div>

                    <div class="space-y-4">
                        @forelse ($content as $i => $item)
                            {{-- LEVEL 1 ITEM --}}
                            <div class="relative border-2 border-purple-200 dark:border-purple-800 rounded-xl overflow-hidden" wire:key="item-{{ $i }}">
                                {{-- Level Indicator --}}
                                <div class="absolute top-0 left-0 w-2 h-full bg-linear-to-b from-purple-500 to-purple-600"></div>
                                
                                <div class="p-5 pl-7 bg-purple-50/50 dark:bg-purple-900/10">
                                    {{-- Header --}}
                                    <div class="flex items-center justify-between mb-4">
                             <div class="flex items-center gap-3">
                                            <div class="p-2 bg-purple-600 rounded-lg">
                                                <x-lucide-layers class="w-4 h-4 text-white" />
                                            </div>
                                            <h4 class="font-bold text-gray-900 dark:text-white">Field #{{ $i + 1 }}</h4>
                                        </div>

                                        <button type="button" wire:click="removeKey({{ $i }})"
                                            class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <x-lucide-trash-2 class="w-5 h-5" />
                                        </button>
                                    </div>

                                    <div class="grid gap-4 md:grid-cols-2">
                                        {{-- KEY --}}
                                        <div>
                                            <x-core::input type="text" label="Key Name" wire:model.live="content.{{ $i }}.key" 
                                                placeholder="e.g. title, price, image" />
                                            <p class="mt-1 text-xs text-gray-500">Unique identifier for this field</p>
                                        </div>

                                        {{-- TYPE --}}
                                        <div>
                                            <x-core::label value="Data Type" />
                                            <select wire:model.live="content.{{ $i }}.type"
                                                class="py-3 px-4 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:border-purple-500 focus:ring-purple-500">
                                                <option value="">Choose type...</option>
                                                <option value="string">📝 Text (String)</option>
                                                <option value="number">🔢 Number</option>
                                                <option value="boolean">✓ True/False (Boolean)</option>
                                                <option value="file">📁 File Upload</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- VALUE --}}
                                    <div class="mt-4">
                                        @if ($item['type'] === 'boolean')
                                            <x-core::label value="Value" />
                                            <select wire:model.live="content.{{ $i }}.value"
                                                class="py-3 px-4 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg">
                                                <option value="">Select...</option>
                                                <option value="true">✓ True</option>
                                                <option value="false">✗ False</option>
                                            </select>

                                        @elseif ($item['type'] === 'file')
                                            <x-core::label value="Upload File" />
                                            <div class="relative">
                                                <input type="file" wire:model="content.{{ $i }}.value" 
                                                    class="block w-full text-sm text-gray-600 file:mr-4 file:py-3 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 dark:file:bg-purple-900/50 dark:file:text-purple-300" />
                                                
                                                <div wire:loading wire:target="content.{{ $i }}.value" class="mt-2 flex items-center gap-2 text-xs text-purple-600">
                                                    <x-lucide-loader-2 class="w-3 h-3 animate-spin" />
                                                    Uploading...
                                                </div>
                                            </div>

                                            @if (is_string($item['value']) && $item['value'])
                                                <a href="{{ Storage::disk('s3')->url($item['value']) }}" target="_blank"
                                                    class="mt-2 inline-flex items-center gap-1 text-xs text-purple-600 hover:text-purple-700 font-medium">
                                                    <x-lucide-external-link class="w-3 h-3" />
                                                    View uploaded file
                                                </a>
                                            @endif
                                        @else
                                            <x-core::input type="text" label="Value" wire:model.live="content.{{ $i }}.value" 
                                                placeholder="Enter field value" />
                                        @endif
                                    </div>

                                    {{-- SUB KEYS (Level 2) --}}
                                    <div class="mt-6 pt-6 border-t border-purple-200 dark:border-purple-700">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-2">
                                                <x-lucide-git-branch class="w-4 h-4 text-blue-600" />
                                                <span class="font-semibold text-sm text-gray-900 dark:text-white">Nested Fields (Level 2)</span>
                                            </div>

                                            <button type="button" wire:click="addSubKey({{ $i }})"
                                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                                <x-lucide-plus class="w-3.5 h-3.5" />
                                                Add Nested
                                            </button>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach ($item['children'] as $j => $child)
                                                <div class="relative border-2 border-blue-200 dark:border-blue-800 rounded-lg overflow-hidden" wire:key="child-{{ $i }}-{{ $j }}">
                                                    <div class="absolute top-0 left-0 w-1.5 h-full bg-linear-to-b from-blue-500 to-blue-600"></div>
                                                    
                                                    <div class="p-4 pl-5 bg-blue-50/50 dark:bg-blue-900/10">
                                                        <div class="flex items-center justify-between mb-3">
                                                            <div class="flex items-center gap-2">
                                                                <div class="p-1.5 bg-blue-600 rounded">
                                                                    <x-lucide-corner-down-right class="w-3.5 h-3.5 text-white" />
                                                                </div>
                                                                <span class="font-medium text-sm text-gray-900 dark:text-white">Sub Field #{{ $j + 1 }}</span>
                                                            </div>

                                                            <button type="button" wire:click="removeSubKey({{ $i }}, {{ $j }})"
                                                                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                                                <x-lucide-x class="w-4 h-4" />
                                                            </button>
                                                        </div>

                                                        <div class="grid gap-3 md:grid-cols-2 mb-3">
                                                            <x-core::input type="text" label="Key" wire:model.live="content.{{ $i }}.children.{{ $j }}.key" 
                                                                placeholder="sub_key" />

                                                            <div>
                                                                <x-core::label value="Type" />
                                                                <select wire:model.live="content.{{ $i }}.children.{{ $j }}.type"
                                                                    class="py-2.5 px-3 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm">
                                                                    <option value="">Type...</option>
                                                                    <option value="string">📝 String</option>
                                                                    <option value="number">🔢 Number</option>
                                                                    <option value="boolean">✓ Boolean</option>
                                                                    <option value="file">📁 File</option>
                                                                    <option value="object">📦 Object (Level 3)</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        {{-- VALUE Level 2 --}}
                                                        @if (($child['type'] ?? 'string') === 'boolean')
                                                            <select wire:model.live="content.{{ $i }}.children.{{ $j }}.value"
                                                                class="py-2.5 px-3 block w-full bg-white dark:bg-gray-700 rounded-lg text-sm">
                                                                <option value="true">✓ True</option>
                                                                <option value="false">✗ False</option>
                                                            </select>

                                                        @elseif (($child['type'] ?? '') === 'file')
                                                            <input type="file" wire:model="content.{{ $i }}.children.{{ $j }}.value"
                                                                class="block w-full text-sm file:mr-4 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-blue-50 file:text-blue-700" />
                                                            
                                                            <div wire:loading wire:target="content.{{ $i }}.children.{{ $j }}.value" class="text-xs text-blue-600 mt-1">
                                                                <x-lucide-loader-2 class="w-3 h-3 animate-spin inline" /> Uploading...
                                                            </div>

                                                            @if (is_string($child['value'] ?? null))
                                                                <a href="{{ Storage::disk('s3')->url($child['value']) }}" target="_blank"
                                                                    class="text-xs text-blue-600 hover:underline inline-flex items-center gap-1 mt-1">
                                                                    <x-lucide-external-link class="w-3 h-3" /> View
                                                                </a>
                                                            @endif

                                                        @elseif (($child['type'] ?? '') !== 'object')
                                                            <x-core::input type="text" label="Value" wire:model.live="content.{{ $i }}.children.{{ $j }}.value" />
                                                        @endif

                                                        {{-- SUB SUB KEYS (Level 3) --}}
                                                        @if (($child['type'] ?? '') === 'object')
                                                            <div class="mt-4 pt-4 border-t border-blue-200 dark:border-blue-700 pl-4">
                                                                <div class="flex items-center justify-between mb-3">
                                                                    <div class="flex items-center gap-1.5">
                                                                        <x-lucide-git-branch-plus class="w-3.5 h-3.5 text-indigo-600" />
                                                                        <span class="text-xs font-medium text-gray-900 dark:text-white">Level 3 Fields</span>
                                                                    </div>

                                                                    <button type="button" wire:click="addSubSubKey({{ $i }}, {{ $j }})"
                                                                        class="px-2.5 py-1 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium rounded-md">
                                                                        <x-lucide-plus class="w-3 h-3 inline" /> Add
                                                                    </button>
                                                                </div>

                                                                <div class="space-y-2">
                                                                    @foreach ($child['children'] ?? [] as $k => $sub)
                                                                        <div class="border border-indigo-200 dark:border-indigo-800 rounded-lg p-3 bg-indigo-50/50 dark:bg-indigo-900/10" wire:key="sub-{{ $i }}-{{ $j }}-{{ $k }}">
                                                                            <div class="flex items-center justify-between mb-2">
                                                                                <span class="text-xs font-medium text-gray-900 dark:text-white">Sub-Sub #{{ $k + 1 }}</span>
                                                                                <button type="button" wire:click="removeSubSubKey({{ $i }}, {{ $j }}, {{ $k }})"
                                                                                    class="text-red-600 text-xs hover:bg-red-50 p-1 rounded">
                                                                                    <x-lucide-x class="w-3 h-3" />
                                                                                </button>
                                                                            </div>

                                                                            <div class="space-y-2">
                                                                                <x-core::input type="text" label="Key" wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.key" />

                                                                                <select wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.type"
                                                                                    class="py-2 px-3 block w-full bg-white dark:bg-gray-700 rounded-lg text-xs">
                                                                                    <option value="string">📝 String</option>
                                                                                    <option value="number">🔢 Number</option>
                                                                                    <option value="boolean">✓ Boolean</option>
                                                                                </select>

                                                                                @if (($sub['type'] ?? 'string') === 'boolean')
                                                                                    <select wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.value"
                                                                                        class="py-2 px-3 block w-full bg-white rounded-lg text-xs">
                                                                                        <option value="true">✓ True</option>
                                                                                        <option value="false">✗ False</option>
                                                                                    </select>
                                                                                @else
                                                                                    <x-core::input type="text" label="Value" wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.value" />
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-900/50 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center">
                                    <x-lucide-database class="w-8 h-8 text-purple-600 dark:text-purple-400" />
                                </div>
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Fields Yet</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Start building your section structure</p>
                                <button type="button" wire:click="addKey"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                    <x-lucide-plus class="w-4 h-4" />
                                    Add First Field
                                </button>
                            </div>
                        @endforelse
                    </div>

                    <div class="flex items-center justify-end mt-6 pt-4">
                        <button type="button" wire:click="addKey"
                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition-all">
                            <x-lucide-plus class="w-4 h-4" />
                            Add Field
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
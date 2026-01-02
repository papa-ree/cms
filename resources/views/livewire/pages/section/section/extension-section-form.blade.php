<div>
    @if(!$editMode)
        <x-core::back-breadcrumb :href="route('bale.cms.sections.index')" label="section list" />
    @endif

    <form x-data="{ 
        sectionName: $wire.entangle('name'), 
        sectionSlug: $wire.entangle('slug'),
        actived: $wire.entangle('actived'),
        }"
        wire:submit="@if(!$editMode) save(Object.fromEntries(new FormData($event.target))) @else update(Object.fromEntries(new FormData($event.target))) @endif"
        class="space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-3 sm:gap-x-6">
            {{-- LEFT --}}
            <div class="col-span-3 sm:col-span-1">
                <x-core::page-container class="space-y-6">
                    <h3 class="font-semibold text-lg">
                        @if(!$editMode)
                            New
                        @else
                            Updated
                        @endif
                        Section
                    </h3>

                    {{-- NAME --}}
                    <div>
                        <x-core::input wire:model="name" x-model="sectionName" label="Name" />
                        <x-core::input-error for="name" />
                    </div>

                    {{-- SLUG --}}
                    <div>
                        <x-core::input wire:model="slug" name="slug" x-slug="sectionName" x-model="sectionSlug"
                            label="Slug" />
                        <x-core::input-error for="slug" />
                    </div>

                    {{-- active toggle --}}
                    <div class="hs-tooltip flex items-center justify-end gap-x-3">
                        <label for="bale-show-extension-section"
                            class="hs-tooltip-toggle relative inline-block w-11 h-6 cursor-pointer">
                            <input type="checkbox" id="bale-show-extension-section" :checked="actived"
                                wire:model='actived' class="peer sr-only">
                            <span
                                class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-emerald-600 dark:bg-neutral-700 dark:peer-checked:bg-emerald-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                            <span
                                class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                        </label>
                        <label for="bale-show-extension-section" class="text-sm text-gray-500 dark:text-neutral-400">
                            Allow Section
                        </label>
                        <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700"
                            role="tooltip">
                            Enable Section
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-x-3">
                        @if ($slug)
                            <x-core::button label="add item" link :href="route('bale.cms.sections.edit-searchable', $slug)"
                                class="items-center justify-center" type="button" />
                        @endif

                        <x-core::button label="save" class="items-center justify-center" type="submit" />
                    </div>
                </x-core::page-container>
            </div>

            {{-- CONTENT JSON BUILDER --}}
            <div class="col-span-3 sm:col-span-2">
                <x-core::page-container class="space-y-4 col-span-2">

                    <div class="flex justify-between items-center">
                        <h3 class="font-semibold text-lg">Content JSON</h3>

                        <x-core::secondary-button label="Add Key" type="button" wire:click="addKey">
                            <x-slot name="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus size-4 mr-2">
                                    <path d="M5 12h14" />
                                    <path d="M12 5v14" />
                                </svg>
                            </x-slot>
                        </x-core::secondary-button>
                    </div>

                    @foreach ($content as $i => $item)
                        <div class="border p-4 rounded-xl space-y-4" wire:key="item-{{ $i }}">

                            {{-- HEADER --}}
                            <div class="flex justify-between">
                                <h4 class="font-semibold">Item #{{ $i + 1 }}</h4>

                                <button type="button" wire:click="removeKey({{ $i }})" class="text-red-600 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="size-6" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" />
                                        <path d="M3 6h18" />
                                        <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" />
                                    </svg>
                                </button>
                            </div>

                            {{-- KEY Level 1 --}}
                            <div>
                                <x-core::input type="text" label="Key" wire:model.live="content.{{ $i }}.key" />
                            </div>

                            {{-- TYPE level 1 --}}
                            <div>
                                <x-core::label value="Type" />
                                <select wire:model.live="content.{{ $i }}.type"
                                    class="py-3 px-4 pe-9 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-transparent dark:text-gray-200 dark:focus:ring-gray-600">
                                    <option selected="">Open this select menu</option>
                                    <option value="string">String</option>
                                    <option value="number">Number</option>
                                    <option value="boolean">Boolean</option>
                                    <option value="file">File</option>
                                </select>
                            </div>

                            {{-- VALUE level 1 --}}
                            <div>
                                @if ($item['type'] === 'boolean')
                                    <x-core::label value="Value" />
                                    <select wire:model.live="content.{{ $i }}.value"
                                        class="py-3 px-4 pe-9 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-transparent dark:text-gray-200 dark:focus:ring-gray-600">
                                        <option selected="">Open this select menu</option>
                                        <option value="true">True</option>
                                        <option value="false">False</option>
                                    </select>

                                @elseif ($item['type'] === 'file')
                                    <x-core::label value="File" />

                                    <input type="file" wire:model="content.{{ $i }}.value" class="block w-full text-sm text-gray-600
                                                                                               file:mr-4 file:py-2 file:px-4
                                                                                               file:rounded-lg file:border-0
                                                                                               file:text-sm file:font-semibold
                                                                                               file:bg-emerald-50 file:text-emerald-700
                                                                                               hover:file:bg-emerald-100" />

                                    {{-- loading indicator --}}
                                    <div wire:loading wire:target="content.{{ $i }}.value" class="text-xs text-gray-500">
                                        Uploading...
                                    </div>

                                    {{-- preview jika sudah ada --}}
                                    @if (is_string($item['value']) && $item['value'])
                                        <p class="text-xs mt-1">
                                            Current file:
                                            <a href="{{ Storage::disk('s3')->url($item['value']) }}" target="_blank"
                                                class="text-emerald-600 underline">
                                                View file
                                            </a>
                                        </p>
                                    @endif
                                @else
                                    <x-core::input type="text" label="Value" wire:model.live="content.{{ $i }}.value" />
                                @endif
                            </div>

                            {{-- SUBKEY LIST --}}
                            <div class="space-y-3">
                                <div class="flex justify-between">
                                    <span class="font-medium">Sub Keys (Nested)</span>

                                    <button type="button" wire:click="addSubKey({{ $i }})"
                                        class="px-2 py-1 bg-green-600 text-white rounded">
                                        + Add Sub Key
                                    </button>
                                </div>

                                @foreach ($item['children'] as $j => $child)
                                    <div class="border p-3 rounded-lg space-y-2" wire:key="child-{{ $i }}-{{ $j }}">

                                        <div class="flex justify-between">
                                            <label class="font-medium text-sm">Sub Key #{{ $j + 1 }}</label>

                                            <button type="button" wire:click="removeSubKey({{ $i }}, {{ $j }})"
                                                class="text-red-600">
                                                Remove
                                            </button>
                                        </div>

                                        {{-- CHILD KEY Level 2 --}}
                                        <x-core::input type="text" label="Key"
                                            wire:model.live="content.{{ $i }}.children.{{ $j }}.key" />

                                        {{-- CHILD TYPE level 2 --}}
                                        <div>
                                            <x-core::label value="Type" />
                                            <select wire:model.live="content.{{ $i }}.children.{{ $j }}.type"
                                                class="py-3 px-4 pe-9 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-transparent dark:text-gray-200 dark:focus:ring-gray-600">
                                                <option value="" selected>Choose type</option>
                                                <option value="string">String</option>
                                                <option value="number">Number</option>
                                                <option value="boolean">Boolean</option>
                                                <option value="file">File</option>
                                                <option value="object">Object (nested)</option>
                                            </select>
                                        </div>

                                        {{-- VALUE level 2 --}}
                                        @if (($child['type'] ?? 'string') === 'boolean')
                                            <x-core::label value="Value" />
                                            <select wire:model.live="content.{{ $i }}.children.{{ $j }}.value"
                                                class="py-3 px-4 pe-9 block w-full bg-gray-100 rounded-lg">
                                                <option value="true">True</option>
                                                <option value="false">False</option>
                                            </select>

                                        @elseif (($child['type'] ?? '') === 'file')
                                            <input type="file" wire:model="content.{{ $i }}.children.{{ $j }}.value"
                                                class="block w-full text-sm text-gray-600" />

                                            <div wire:loading wire:target="content.{{ $i }}.children.{{ $j }}.value"
                                                class="text-xs text-gray-500">
                                                Uploading...
                                            </div>

                                            @if (is_string($child['value'] ?? null))
                                                <a href="{{ Storage::disk('s3')->url($child['value']) }}" target="_blank"
                                                    class="text-xs text-emerald-600 underline">
                                                    View file
                                                </a>
                                            @endif

                                        @elseif (($child['type'] ?? '') !== 'object')
                                            <x-core::input type="text" label="Value"
                                                wire:model.live="content.{{ $i }}.children.{{ $j }}.value" />
                                        @endif

                                        {{-- ====================== --}}
                                        {{-- SUB SUB KEYS (Level 3) --}}
                                        {{-- ====================== --}}
                                        @if (($child['type'] ?? '') === 'object')
                                            <div class="pl-4 border-l-2 mt-3 space-y-3">

                                                <div class="flex justify-between">
                                                    <span class="text-sm font-medium">Sub Sub Keys</span>

                                                    <button type="button" wire:click="addSubSubKey({{ $i }}, {{ $j }})"
                                                        class="px-2 py-1 bg-blue-600 text-white rounded text-xs">
                                                        + Add Sub Sub Key
                                                    </button>
                                                </div>

                                                @foreach ($child['children'] ?? [] as $k => $sub)
                                                    <div class="border p-3 rounded-lg space-y-2"
                                                        wire:key="sub-{{ $i }}-{{ $j }}-{{ $k }}">

                                                        <div class="flex justify-between">
                                                            <label class="font-medium text-xs">Sub Sub Key #{{ $k + 1 }}</label>
                                                            <button type="button"
                                                                wire:click="removeSubSubKey({{ $i }}, {{ $j }}, {{ $k }})"
                                                                class="text-red-600 text-xs">
                                                                Remove
                                                            </button>
                                                        </div>

                                                        <x-core::input type="text" label="Key"
                                                            wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.key" />

                                                        <div>
                                                            <x-core::label value="Type" />
                                                            <select
                                                                wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.type"
                                                                class="py-3 px-4 pe-9 block w-full bg-gray-100 border-transparent rounded-lg text-sm focus:border-emerald-500 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-transparent dark:text-gray-200 dark:focus:ring-gray-600">
                                                                <option value="string">String</option>
                                                                <option value="number">Number</option>
                                                                <option value="boolean">Boolean</option>
                                                            </select>

                                                        </div>

                                                        @if (($sub['type'] ?? 'string') === 'boolean')
                                                            <select
                                                                wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.value"
                                                                class="py-2 px-3 block w-full bg-gray-100 rounded-lg text-sm">
                                                                <option value="true">True</option>
                                                                <option value="false">False</option>
                                                            </select>
                                                        @else
                                                            <x-core::input type="text" label="Value"
                                                                wire:model.live="content.{{ $i }}.children.{{ $j }}.children.{{ $k }}.value" />
                                                        @endif

                                                    </div>
                                                @endforeach

                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            </div>


                        </div>
                    @endforeach

                </x-core::page-container>
            </div>
        </div>

        {{-- SAVE --}}
        <div class="flex items-center justify-end gap-x-6">
            <x-core::secondary-button label="Add Key" type="button" wire:click="addKey">
                <x-slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-plus-icon lucide-plus size-4 mr-2">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                </x-slot>
            </x-core::secondary-button>

            <x-core::button label="save" class="items-center justify-center" type="submit" />
        </div>

    </form>

</div>
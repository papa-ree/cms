<div>
    <x-core::back-breadcrumb :href="route('bale.cms.sections.index')" label="section list" />

    <form id="searchableForm"
        wire:submit="@if(!$editMode) save(Object.fromEntries(new FormData($event.target))) @else update(Object.fromEntries(new FormData($event.target))) @endif">
        <div class="space-y-6" x-data="{ sectionName: $wire.entangle('name'), sectionSlug: $wire.entangle('slug') }">
            <div class="grid grid-cols-1 md:grid-cols-2 sm:gap-x-6">
                {{-- LEFT --}}
                <div class="col-span-2 sm:col-span-1">
                    <x-core::page-container class="space-y-6">
                        <h3 class="font-semibold text-lg">New Searchable Section</h3>
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
                    </x-core::page-container>
                </div>
                {{-- CARD: Manage Keys --}}
                <div class="col-span-2 sm:col-span-1">
                    <x-core::page-container class="space-y-6">

                        <h2 class="font-semibold text-lg">Pengaturan Key</h2>
                        <x-core::label value="Add New Key" />

                        <div class="flex gap-2">
                            <div class="">
                                <x-core::input wire:model="newKey" @keydown.enter='$wire.addKey' />
                            </div>
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

                        <div class="flex flex-wrap gap-2 mt-3">
                            @foreach ($availableKeys as $i => $key)
                                <div
                                    class="inline-flex flex-nowrap items-center bg-white border border-gray-200 rounded-full p-1.5 dark:bg-gray-900 dark:border-gray-700">
                                    <div class="whitespace-nowrap ms-1.5 text-sm font-medium text-gray-800 dark:text-white">
                                        {{ $key }}
                                    </div>
                                    <div wire:click="removeKey({{ $i }})"
                                        class="ms-2.5 inline-flex justify-center items-center size-5 rounded-full text-gray-800 bg-gray-200 hover:bg-gray-300 focus:outline-hidden focus:ring-2 focus:ring-gray-400 dark:bg-gray-700/50 dark:hover:bg-gray-700 dark:text-gray-400 cursor-pointer">
                                        <svg class="shrink-0 size-3" xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M18 6 6 18"></path>
                                            <path d="m6 6 12 12"></path>
                                        </svg>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-core::page-container>
                </div>
            </div>

            {{-- CARD: Items --}}
            <x-core::page-container class="space-y-4">
                <h2 class="font-semibold text-lg">Items (Content)</h2>

                <x-core::secondary-button label="Add Item" type="button" wire:click="addItem">
                    <x-slot name="icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="lucide lucide-plus-icon lucide-plus size-4 mr-2">
                            <path d="M5 12h14" />
                            <path d="M12 5v14" />
                        </svg>
                    </x-slot>
                </x-core::secondary-button>

                <div class="space-y-6 mt-4">
                    @foreach ($items as $i => $item)
                        <div class="border rounded-lg p-4 bg-gray-50 dark:bg-gray-800">
                            <div class="flex justify-between mb-3">
                                <h3 class="font-medium">Item #{{ $i + 1 }}</h3>
                                <button wire:click="removeItem({{ $i }})" class="text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </div>
                            {{-- Dynamic key/value fields --}}
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                @foreach ($availableKeys as $key)
                                    @if ($key == 'date')
                                        <div>
                                            <x-core::input type="date" wire:model="items.{{ $i }}.{{ $key }}" label="{{ $key }}" />
                                        </div>
                                    @else
                                        <div>
                                            <x-core::input wire:model="items.{{ $i }}.{{ $key }}" label="{{ $key }}" />
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-core::page-container>

            {{-- Submit --}}
            <div class="flex justify-end">
                <x-core::button label="save" type="submit" />
            </div>
        </div>
    </form>

    <script>
        document.getElementById( 'searchableForm' ).addEventListener( 'keydown', function ( event )
        {
            // Check if the pressed key is Enter (keyCode 13 or key 'Enter')
            if ( event.keyCode === 13 || event.key === 'Enter' ) {
                // Prevent the default form submission behavior
                event.preventDefault();
                // Optionally, you can add other actions here, like focusing on the next input field
                // or displaying a message.
            }
        } );
    </script>
</div>
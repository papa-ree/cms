<div>

    <x-core::back-breadcrumb :href="$parent ? route('bale.cms.navigations.edit', $parent->slug) : route('bale.cms.navigations.index')" :label="$parent ? $parent->name : 'navigations list'" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        {{-- edit form --}}
        <x-core::page-container class="col-span-1">
            <div class="mb-3 ">
                <p class="text-sm antialiased font-semibold">Navigation Detail</p>
            </div>

            <form wire:submit="update(Object.fromEntries(new FormData($event.target)))"
                x-data="{ selectUrl: $wire.entangle('url_mode'), navigationTitle: $wire.entangle('name'), navigationSlug: $wire.entangle('slug') }"
                x-cloak>
                <div class="mb-4 sm:mb-6">
                    <x-core::input label="navigation name" x-model="navigationTitle" wire:model='name' />
                    <x-core::input-error for="name" class="mt-2" />
                </div>

                <div class="mb-4 sm:mb-6">
                    <x-core::input label="navigation slug" wire:model='slug' name="slug" x-slug="navigationTitle"
                        x-model="navigationSlug" />
                    <x-core::input-error for="slug" class="mt-2" />
                </div>

                <x-core::label value="Navigation Mode" />
                <div class="p-4 border dark:border-gray-600 rounded-xl">
                    <div class="overflow-y-auto" wire:ignore>
                        <div class="grid gap-2 sm:grid-cols-2">
                            <label for="url_mode" @click="selectUrl=true"
                                class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-500 dark:text-neutral-400">
                                <span class="text-sm text-gray-500 dark:text-neutral-400">Use URL</span>
                                <input type="radio" name="url_mode" wire:model='url_mode' value="1"
                                    class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-emerald-600 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-gray-500 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-emerald-400"
                                    id="url_mode">
                            </label>
                            <label for="page_mode" @click="selectUrl=false"
                                class="flex w-full p-3 text-sm bg-white border border-gray-200 rounded-lg focus:border-emerald-500 focus:ring-emerald-500 dark:bg-gray-700 dark:border-gray-500 dark:text-neutral-400">
                                <span class="text-sm text-gray-500 dark:text-neutral-400">Select Page</span>
                                <input type="radio" name="url_mode" wire:model='url_mode' value="0"
                                    class="shrink-0 ms-auto mt-0.5 border-gray-200 rounded-full text-emerald-600 focus:ring-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-700 dark:border-gray-500 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-emerald-400"
                                    id="page_mode">
                            </label>
                        </div>
                    </div>
                    <x-core::input-error for="url_mode" class="mt-2" />

                    <div class="mt-3" x-show="selectUrl==true">
                        <x-core::input useInlineAddon="true" wire:model="url" addon="https://" />
                        <x-core::input-error for="url" class="mt-2" />
                    </div>

                    {{-- select page --}}
                    <div class="mt-3" x-show="selectUrl==false">
                        <x-core::select-dropdown label="Select Page" :items="$this->availablePages" valueField="slug"
                            labelField="title" model="page_slug" />

                        <x-core::input-error for="page_slug" class="mt-2" />
                    </div>

                </div>

                <div class="flex justify-end mt-6">
                    <x-core::button type="submit" label="Update" />
                </div>
            </form>
        </x-core::page-container>

        {{-- nav item list --}}
        @if (!$parent)
            <x-core::page-container class="col-span-1">
                <div class="mb-3">
                    <p class="text-sm antialiased font-semibold">Sub Navigation</p>
                </div>

                <x-core::sortable-item :sortableItems="$this->availableChilds" itemLabel="name"
                    route="bale.cms.navigations.edit" />

                <div class="flex justify-end mt-6">
                    <x-core::button type="button" link :href="route('bale.cms.navigations.create', $slug)"
                        label="Create Sub Navigation" />
                </div>
            </x-core::page-container>
        @endif
    </div>
</div>
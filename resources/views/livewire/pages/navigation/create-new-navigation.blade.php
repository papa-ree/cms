<div>
    <x-core::back-breadcrumb :href="route('bale.cms.navigations.index')" label="navigation list" />

    <x-core::page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8">
            <div class="max-w-xl mx-auto ">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
                        Create Navigation
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-neutral-400">
                        Navigations are multi-level lists of links that can be used to build navbars, footers, sitemaps,
                        and other forms of frontend navigation.
                    </p>
                </div>

                <div class="mt-12">
                    <form wire:submit="store(Object.fromEntries(new FormData($event.target)))"
                        x-data="{ navigationName: '', navigationSlug: '' }">
                        <div class="mb-4 sm:mb-8">
                            <x-core::input label="navigation name" x-model="navigationName" wire:model='name' />
                            <x-core::input-error for="name" />
                        </div>

                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="navigation slug" wire:model='slug' name="slug" x-slug="navigationName"
                                x-model="navigationSlug" />
                            <x-core::input-error for="slug" />
                        </div>

                        <div class="flex justify-center">
                            <x-core::button label="create navigation" spinner type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-core::page-container>
</div>
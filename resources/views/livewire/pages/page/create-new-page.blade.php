<div>
    <a href="{{ route('bale.cms.pages.index') }}" wire:navigate.hover
        class="flex items-center justify-start mb-5 text-sm gap-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="size-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
        </svg>
        <div class="capitalize">page list</div>
    </a>

    <x-core::page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8">
            <div class="max-w-xl mx-auto">
                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
                        Create Page
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-neutral-400">
                        This page allows users to create and manage pages for websites. Users can set page titles and
                        slugs.
                    </p>
                </div>

                <div class="mt-12">
                    <form wire:submit="store(Object.fromEntries(new FormData($event.target)))"
                        x-data="{ pageTitle: '', pageSlug: '' }">
                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="page title" wire:model='title' x-model="pageTitle" autofocus />
                            <x-core::input-error for="title" />
                        </div>

                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="page slug" wire:model='slug' name="slug" x-slug="pageTitle"
                                x-model="pageSlug" />
                            <x-core::input-error for="slug" />
                        </div>

                        <div class="flex justify-center">
                            <x-core::button label="create post" spinner type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-core::page-container>
</div>
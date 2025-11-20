<div>
    <x-bale.back-breadcrumb :href="route('bale.cms.posts.index')" label="post list" />


    <x-bale.page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8">
            <div class="max-w-xl mx-auto space-y-8">

                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
                        Create Post
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-neutral-400">
                        This post allows users to create and manage pages for websites. Users can set post titles.
                    </p>
                </div>

                <div class="mt-12">
                    <form wire:submit="store(Object.fromEntries(new FormData($event.target)))"
                        x-data="{ postTitle: '' }" x-cloak>
                        <div class="mb-4 sm:mb-6">
                            <x-bale.input label="post title" wire:model='title' x-model="postTitle" autofocus />

                            <x-input-error for="title" />
                        </div>

                        <div class="flex justify-center">
                            <x-bale.button label="create post" spinner type="submit" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-bale.page-container>
</div>
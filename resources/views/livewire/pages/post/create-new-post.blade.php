<div>
    <x-core::breadcrumb :items="[['label' => 'Posts', 'route' => 'bale.cms.posts.index']]" active="Create New Post" />

    <x-core::page-container>
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
                    <form @submit.prevent="$wire.call('store', Object.fromEntries(new FormData($event.target)))"
                        x-data="{ postTitle: '', postSlug: '' }" x-cloak>
                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="post title" wire:model='title' x-model="postTitle" name="title"
                                autofocus />
                            <x-core::input-error for="title" />
                        </div>

                        <div class="mb-4 sm:mb-6">
                            <x-core::input label="page slug" wire:model='slug' name="slug" x-slug="postTitle"
                                x-model="postSlug" />
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
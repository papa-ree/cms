<header
    class="lg:sticky lg:top-0 fixed bottom-0 inset-x-0 flex flex-wrap z-[48] w-full bg-white lg:border-b border-t text-sm py-2.5 sm:py-4 dark:bg-gray-800 dark:border-gray-700">
    <nav class="flex items-center justify-between w-full px-4 mx-auto basis-full sm:px-6 md:px-8" aria-label="Global">
        <a href="/cms/posts" wire:navigate.hover type="button" class="text-gray-500 hover:text-gray-600">
            Kembali ke post
        </a>

        <div class="">
            <x-bale.button label="Update" form="formPost" />
        </div>
    </nav>
</header>
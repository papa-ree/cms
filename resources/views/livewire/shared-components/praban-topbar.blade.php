<header
    class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 dark:bg-gray-800 dark:border-gray-700">
    <nav class="flex items-center w-full px-4 mx-auto basis-full sm:px-6 md:px-8" aria-label="Global">
        <div class="flex items-center mr-5 space-x-3 lg:mr-0 lg:hidden">
            <button type="button" class="text-gray-500 hover:text-gray-600" data-hs-overlay="#application-sidebar"
                aria-controls="application-sidebar" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="w-5 h-5" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <a class="flex-none text-sm font-semibold dark:text-white" href="#" aria-label="Brand">Bale Panel</a>
        </div>

        <div class="flex items-center justify-end w-full ml-auto sm:justify-between sm:gap-x-3 sm:order-3">
            <a href="/cms/posts" wire:navigate.hover>kembali</a>
            <x-bale.dark-mode-toggle />
        </div>
    </nav>
</header>
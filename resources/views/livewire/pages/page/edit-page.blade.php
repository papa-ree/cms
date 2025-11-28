<div>
    <form wire:submit="update(Object.fromEntries(new FormData($event.target)))"
        x-data="{ pageTitle: $wire.entangle('title'), pageSlug: $wire.entangle('slug'), locked: $wire.entangle('locked') }"
        id="update">

        <div class="grid grid-cols-12 px-6 space-y-4 xl:px-0 gap-x-0 xl:space-y-0 ">
            <div class="col-span-12 px-0 py-4 antialiased text-gray-800 xl:col-span-3 xl:px-8 dark:text-white ">

                <div class="flex justify-end mb-2">
                    {{-- <button type="button" @click="locked=!locked; $wire.pageLockingToggle()" class="flex
                        justify-center items-center size-[40px] text-sm font-medium rounded-lg border border-gray-200
                        bg-white text-gray-800 shadow-sm hover:bg-gray-50 focus:outline-none focus:bg-gray-50
                        disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-900 dark:border-neutral-700
                        dark:text-white dark:hover:bg-neutral-800 dark:focus:bg-neutral-800">

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-show="locked"
                            stroke-width="1.5" stroke="currentColor" class="size-4 text-cyan-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-show="!locked"
                            stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 10.5V6.75a4.5 4.5 0 1 1 9 0v3.75M3.75 21.75h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H3.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </button> --}}
                </div>

                <div class="mb-4 sm:mb-6">
                    <x-core::input label="page title" x-bind:readonly="locked == true" wire:model="title"
                        x-model="pageTitle" />
                    <x-core::input-error for="title" />
                </div>

                <div class="mb-4 sm:mb-6">
                    <x-core::input label="page slug" x-bind:readonly="locked == true" wire:model="slug" name="slug"
                        x-slug="pageTitle" x-model="pageSlug" />
                    <x-core::input-error for="slug" />
                </div>

            </div>

            <div
                class="static col-span-12 py-4 pl-4 antialiased text-gray-800 bg-white border border-gray-200 xl:col-span-9 lg:pl-0 lg:px-4 md:px-0 lg:overflow-y-auto lg:col-span-5 rounded-xl dark:bg-gray-800 dark:text-white dark:border-gray-700">

                <div wire:ignore id="editorjs"
                    class="bg-white dark:bg-gray-800 lg:max-h-[85vh] max-h-[90vh] md:overflow-y-scroll md:scrollbar-thin scrollbar-thumb-rounded-full scrollbar-track-rounded-full scrollbar-thumb-gray-700 scrollbar-track-gray-300">
                </div>
                <x-core::input-error for="content" />
            </div>

        </div>

    </form>

    @script
    <script>

        document.addEventListener( 'livewire:initialized', () =>
        {
            initEditor();
        } );

        document.addEventListener( 'livewire:navigated', () =>
        {
            if ( window.__editorInitialized ) return;

            window.__editorInitialized = true;
            initEditor();

        } );

        function initEditor ()
        {
            if ( window.editorInitialized ) return;
            window.editorInitialized = true;
            var token = "{{ csrf_token()}}"
            const data = @js($content);
            const editor = new EditorJS( {
                holder: 'editorjs', tools: {
                    list: List,
                    image:
                    {
                        class: ImageTool,
                        config: {
                            additionalRequestHeaders: {
                                "X-CSRF-TOKEN": token
                            },

                            endpoints: {
                                byFile: '/cms/editorjs/upload', // URL Laravel untuk upload file 
                                byUrl: '/cms/editorjs/fetchUrl', // opsional kalau mau fetch by URL 
                            },

                            field: 'image',
                            types: 'image/*',
                            captionPlaceholder: 'Tambahkan keterangan gambar...',
                        },
                    },
                },
                data: data, // <- tampilkan konten dari database 
                onChange: async ( api ) =>
                {
                    const savedData = await api.saver.save();
                    $wire.set( 'content', savedData );
                },
            } );
        }
    </script>
    @endscript

</div>
<div>
    <form wire:submit="update(Object.fromEntries(new FormData($event.target)))" class="" id="formPost"
        x-data="{ postTitle: $wire.entangle('title'), postSlug: $wire.entangle('slug').live, showSetting: false }">

        <div class="grid grid-cols-7 space-y-0 lg:gap-x-8 gap-x-0 lg:space-y-0">

            <div
                class="col-span-7 lg:block hidden p-4 antialiased text-gray-800 bg-white border border-gray-200 lg:p-8 md:p-6 rounded-xl lg:col-span-2 sm:p-6 dark:bg-gray-800 dark:text-white dark:border-gray-700 lg:min-h-[85vh]">

                {{-- post title --}}
                <div class="mb-4 sm:mb-6">
                    <x-core::input wire:model='title' placeholder="post title" label="Post title" x-model="postTitle" />
                    <div class="flex items-center justify-end">
                        <button class="flex items-center mt-2 cursor-pointer text-sm gap-x-2 dark:text-neutral-500"
                            type="button" @click="showSetting=!showSetting"
                            :class="showSetting ? 'text-emerald-500 font-semibold' : 'text-gray-500 font-medium'">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-settings-2">
                                <path d="M20 7h-9" />
                                <path d="M14 17H5" />
                                <circle cx="17" cy="17" r="3" />
                                <circle cx="7" cy="7" r="3" />
                            </svg>
                            Advance Settings
                        </button>
                    </div>
                    <x-core::input-error for="title" />
                </div>

                {{-- post slug --}}
                <div x-show="showSetting" x-collapse>
                    <x-core::input label="permalink" wire:model='slug' name="slug" x-slug="postTitle"
                        x-model="postSlug" />
                    <x-core::input-error for="slug" />

                    <div class="flex items-center mt-2 mb-4 text-sm sm:mb-6 gap-x-2">
                        <span class="text-gray-700 dark:text-neutral-500">
                            sesuaikan permalink untuk mengarahkan
                            link
                            ke artikel anda
                        </span>
                        <div class="hs-tooltip [--placement:top] text-pretty">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-circle-help">
                                <circle cx="12" cy="12" r="10" />
                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                                <path d="M12 17h.01" />
                            </svg>
                            <span
                                class="absolute z-10 invisible inline-block max-w-44 px-2 py-1 text-xs font-medium text-white transition-opacity bg-gray-900 rounded shadow-sm opacity-0 hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible dark:bg-neutral-700"
                                role="tooltip">
                                Permalink adalah URL tetap yang mengarahkan ke suatu artikel atau sumber daring.
                            </span>
                        </div>
                    </div>
                </div>

                {{-- post thumbnail --}}
                <div class="space-y-3 sm:mb-10" x-data="{ showUploadZone: $wire.entangle('show_upload_zone').live }">
                    <x-core::label value="post thumbnail preview" />
                    @if ($thumbnail)
                        <div x-show="!showUploadZone"
                            class="relative flex items-center justify-center overflow-hidden transition-all duration-500 ease-in-out transform rounded-lg shadow-md cursor-pointer group hover:shadow-slate-500">
                            <img loading="lazy"
                                class="object-cover object-center w-full h-40 max-w-full transition-all duration-500 ease-in-out transform bg-center bg-cover rounded-lg group-hover:scale-125"
                                src="{{ route('media.show', ['path' => session('bale_active_slug') . '//thumbnails/' . $thumbnail]) ?? null }}"
                                alt="{{ $title }}" loading="lazy">
                            <div wire:click='deleteThumbnail'
                                class="absolute z-20 hidden p-1 text-white transition-all duration-500 ease-in-out rounded-full group-hover:block bg-red-400/80 hover:bg-white/80 hover:text-red-400 top-1 right-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </div>
                        </div>
                    @endif

                    <div x-show="showUploadZone">
                        <x-cms::filepond wire:model="thumbnail_new" allowImagePreview imagePreviewMaxHeight="200"
                            allowFileTypeValidation acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']"
                            allowFileSizeValidation maxFileSize="512kb" />
                        <x-core::input-error for="thumbnail_new" />
                    </div>
                </div>

                <div class="hidden lg:block">
                    <x-core::button label="update" type="submit" />
                </div>

            </div>

            <div
                class="col-span-7 py-4 pl-4 antialiased text-gray-800 bg-white border border-gray-200 lg:pl-0 lg:px-4 md:px-0 lg:overflow-y-auto lg:col-span-5 rounded-xl dark:bg-gray-800 dark:text-white dark:border-gray-700">

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

        function initEditor ()
        {
            var token = "{{ csrf_token()}}"
            const data = @js($content);
            const editor = new EditorJS( {
                holder: 'editorjs', tools: {
                    List: {
                        class: List,
                        inlineToolbar: true,
                        config: {
                            defaultStyle: 'unordered'
                        },
                    },
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
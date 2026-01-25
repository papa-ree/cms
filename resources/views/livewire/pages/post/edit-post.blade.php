<div>
    {{-- Help Guide --}}
    <div
        class="mb-6 p-5 bg-linear-to-r from-amber-50 to-orange-50 dark:from-amber-900/20 dark:to-orange-900/20 border border-amber-200 dark:border-amber-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-amber-600 rounded-xl shadow-lg">
                <x-lucide-pen-tool class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Post Editor Guide</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Edit your post content using the powerful Bale Editor. Fill in the metadata on the left, then create
                    your content on the right.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-amber-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Title & slug are auto-synced</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-amber-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Content auto-saves on change</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit="update" id="formPost" x-data="{ 
            postTitle: $wire.entangle('title'), 
            postSlug: $wire.entangle('slug').live, 
            showSetting: false,
            isSaving: false 
        }" @submit="isSaving = true" x-on:save-complete.window="isSaving = false">

        <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
            {{-- LEFT SIDEBAR: Post Metadata (Sticky) --}}
            <div class="lg:col-span-2">
                <div
                    class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 lg:sticky lg:top-24 space-y-6 max-h-[calc(100vh-7rem)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                    {{-- Header --}}
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="p-2.5 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-md">
                            <x-lucide-file-text class="w-5 h-5 text-white" />
                        </div>
                        <div>
                            <h3 class="font-bold text-lg text-gray-900 dark:text-white">Post Details</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Metadata & settings</p>
                        </div>
                    </div>

                    {{-- Post Title --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <x-lucide-type class="w-4 h-4 text-gray-400" />
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Post Title *</label>
                        </div>
                        <x-core::input wire:model='title' placeholder="Enter post title..." x-model="postTitle" />
                        <x-core::input-error for="title" />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Main title for your post</p>
                    </div>

                    {{-- Advanced Settings Toggle --}}
                    <button
                        class="flex items-center gap-2 w-full p-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-900/50 dark:hover:bg-gray-900 rounded-lg transition-colors"
                        type="button" @click="showSetting=!showSetting"
                        :class="showSetting ? 'ring-2 ring-blue-500 dark:ring-blue-400' : ''">
                        <x-lucide-settings class="w-4 h-4" />
                        <span class="flex-1 text-left text-sm font-medium"
                            :class="showSetting ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300'">
                            Advanced Settings
                        </span>
                        <x-lucide-chevron-down class="w-4 h-4 transition-transform" />
                    </button>

                    {{-- Post Slug (Collapsed) --}}
                    <div x-show="showSetting" x-collapse>
                        <div
                            class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl space-y-3">
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-link class="w-4 h-4 text-blue-600" />
                                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Permalink /
                                    Slug</label>
                                <div class="hs-tooltip">
                                    <button type="button" class="hs-tooltip-toggle">
                                        <x-lucide-info class="w-3.5 h-3.5 text-blue-600" />
                                    </button>
                                    <span
                                        class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-2 px-3 bg-gray-900 text-xs text-white rounded-lg shadow-lg dark:bg-gray-700"
                                        role="tooltip">
                                        Permalink is the permanent URL for this post
                                    </span>
                                </div>
                            </div>
                            <x-core::input wire:model='slug' name="slug" x-slug="postTitle" x-model="postSlug"
                                placeholder="auto-generated-from-title" />
                            <x-core::input-error for="slug" />
                            <p class="text-xs text-blue-700 dark:text-blue-400">
                                Auto-generated from title. Customize if needed.
                            </p>
                        </div>
                    </div>

                    {{-- Post Thumbnail --}}
                    <div x-data="{ showUploadZone: $wire.entangle('show_upload_zone').live }">
                        <div class="flex items-center gap-2 mb-3">
                            <x-lucide-image class="w-4 h-4 text-gray-400" />
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Featured Image</label>
                        </div>

                        @if ($thumbnail)
                            <div x-show="!showUploadZone"
                                class="relative group overflow-hidden rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-blue-400 dark:hover:border-blue-600 transition-all">
                                <img loading="lazy"
                                    class="w-full h-48 object-cover object-center group-hover:scale-105 transition-transform duration-300"
                                    src="{{ \Bale\Core\Support\Cdn::url('thumbnails/' . $thumbnail) }}" alt="{{ $title }}">
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button wire:click='deleteThumbnail' type="button"
                                        class="absolute top-3 right-3 p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-lg transition-colors">
                                        <x-lucide-trash-2 class="w-4 h-4" />
                                    </button>
                                </div>
                            </div>
                        @endif

                        <div x-show="showUploadZone">
                            <x-cms::filepond wire:model="thumbnail_new" allowImagePreview imagePreviewMaxHeight="200"
                                allowFileTypeValidation acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']"
                                allowFileSizeValidation maxFileSize="512kb" />
                            <x-core::input-error for="thumbnail_new" />
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Max 512KB. Formats: PNG, JPG, JPEG
                            </p>
                        </div>
                    </div>

                    {{-- Submit Button - Hidden (moved to topbar) --}}
                    {{-- Desktop/Tablet: In topbar --}}
                    {{-- Mobile: In bottom bar --}}
                </div>
            </div>

            {{-- RIGHT: EditorJS Content Area --}}
            <div class="lg:col-span-5">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                    {{-- Editor Header --}}
                    <div
                        class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-amber-50 to-orange-50 dark:from-amber-900/10 dark:to-orange-900/10 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 md:gap-3">
                            <div class="p-2 md:p-2.5 bg-linear-to-br from-amber-500 to-amber-600 rounded-lg shadow-md">
                                <x-lucide-file-edit class="w-4 md:w-5 h-4 md:h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Content Editor</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Write your post content</p>
                            </div>
                        </div>
                        <div
                            class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-full">
                            <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                            <span class="text-xs font-medium text-green-700 dark:text-green-400">Auto-save ON</span>
                        </div>
                    </div>

                    {{-- Editor Toolbar Guide --}}
                    <div
                        class="px-6 py-3 bg-blue-50 dark:bg-blue-900/10 border-b border-blue-200 dark:border-blue-800/50">
                        <div class="flex items-start gap-2 text-xs text-blue-700 dark:text-blue-400">
                            <x-lucide-lightbulb class="w-4 h-4 mt-0.5" />
                            <div>
                                <span class="font-semibold">Quick tip:</span>
                                <span>Press <kbd
                                        class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-blue-300 rounded text-blue-800 dark:text-blue-300 font-mono">Content
                                        Editor</kbd> to start typing, and use <kbd
                                        class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-blue-300 rounded text-blue-800 dark:text-blue-300 font-mono">+</kbd>
                                    button on the left.</span>
                            </div>
                        </div>
                    </div>

                    {{-- EditorJS Container --}}
                    <div wire:ignore id="editorjs"
                        class="px-6 py-8 bg-white dark:bg-gray-800 min-h-[70vh] max-h-[70vh] overflow-y-auto prose prose-slate dark:prose-invert max-w-none
                        scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                    </div>

                    <x-core::input-error for="content" />

                    {{-- Editor Footer --}}
                    <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-1.5">
                                    <x-lucide-text class="w-3.5 h-3.5" />
                                    <span>Editor</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <x-lucide-image class="w-3.5 h-3.5" />
                                    <span>Image support</span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <x-lucide-list class="w-3.5 h-3.5" />
                                    <span>Lists</span>
                                </div>
                            </div>
                            <span class="text-gray-500">Last edited: {{ $updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
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
            var token = "{{ csrf_token() }}"
            const data = @js($content);
            const editor = new EditorJS( {
                holder: 'editorjs',
                tools: {
                    List: {
                        class: List,
                        inlineToolbar: true,
                        config: {
                            defaultStyle: 'unordered'
                        },
                    },
                    image: {
                        class: ImageTool,
                        config: {
                            additionalRequestHeaders: {
                                "X-CSRF-TOKEN": token
                            },
                            endpoints: {
                                byFile: '/cms/editorjs/upload',
                                byUrl: '/cms/editorjs/fetchUrl',
                            },
                            field: 'image',
                            types: 'image/*',
                            captionPlaceholder: 'Tambahkan keterangan gambar...',
                        },
                    },
                },
                data: data,
                onChange: async ( api ) =>
                {
                    const savedData = await api.saver.save();
                    $wire.set( 'content', savedData );
                },
                placeholder: 'Start writing your post content here...'
            } );
        }
    </script>
    @endscript
</div>
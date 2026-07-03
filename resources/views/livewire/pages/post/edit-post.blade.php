<div>
    <style>
        /*
         * Editor.js Dark Mode Overrides
         * Strategy: override only color-related properties (background, color, border-color)
         * while preserving all EditorJS native sizing, spacing, border-radius, and layout.
         */

        /* ─── Editor Content ──────────────────────────────────────── */
        .dark .ce-block {
            color: #e2e8f0;
        }

        /* Highlight selection (text blocking) */
        .dark #editorjs *::selection {
            background-color: rgba(59, 130, 246, 0.6);
            /* vibrant blue with transparency */
            color: #ffffff;
        }

        .dark .ce-block--selected .ce-block__content {
            background-color: rgba(51, 65, 85, 0.6);
            /* slate-700 — ensure high visibility */
        }

        .dark .ce-paragraph[data-placeholder]:empty::before {
            color: #475569;
            /* slate-600 */
        }

        /* ─── Toolbar (+ button & settings ⋮) ─────────────────────── */
        .dark .ce-toolbar__plus,
        .dark .ce-toolbar__settings-btn {
            color: #94a3b8;
            /* slate-400 */
            background-color: transparent;
        }

        .dark .ce-toolbar__plus:hover,
        .dark .ce-toolbar__settings-btn:hover {
            color: #f1f5f9;
            background-color: #334155;
            /* slate-700 */
        }

        /* ─── Toolbox Popover (the + menu) ─────────────────────────── */
        .dark .ce-popover,
        .dark .ce-popover--inline {
            background-color: #1e293b;
            /* slate-800 */
            border-color: #334155;
            /* slate-700 */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
        }

        .dark .ce-popover__container {
            background-color: #1e293b;
            /* slate-800 */
            border-color: #334155;
        }

        .dark .ce-popover__search .cdx-search-field {
            background-color: #0f172a;
            /* slate-900 */
            border-color: #334155;
        }

        .dark .ce-popover__search .cdx-search-field__icon {
            color: #64748b;
            /* slate-500 */
        }

        .dark .ce-popover__search .cdx-search-field__input {
            background-color: transparent;
            color: #f1f5f9;
        }

        .dark .ce-popover__search .cdx-search-field__input::placeholder {
            color: #64748b;
        }

        .dark .ce-popover__nothing-found-message {
            color: #64748b;
            /* slate-500 */
            background-color: transparent;
        }

        /* ─── Popover Items ─────────────────────────────────────────── */
        .dark .ce-popover-item {
            color: #e2e8f0;
            /* slate-200 */
        }

        .dark .ce-popover-item:hover,
        .dark .ce-popover-item--focused {
            background-color: #334155;
            /* slate-700 */
        }

        .dark .ce-popover-item--active {
            background-color: #1e3a5f;
            /* subtle blue tint, stays readable */
        }

        .dark .ce-popover-item__icon {
            background-color: #334155;
            /* slate-700 */
            color: #cbd5e1;
            /* slate-300 */
            box-shadow: none;
        }

        .dark .ce-popover-item--active .ce-popover-item__icon {
            background-color: #1d4ed8;
            /* blue-700 */
            color: #fff;
        }

        .dark .ce-popover-item__title {
            color: #e2e8f0;
            /* slate-200 */
        }

        .dark .ce-popover-item__secondary-title {
            color: #64748b;
            /* slate-500 — like EditorJS's native muted hint */
        }

        /* HTML wrapper inside popover (link input row) */
        .dark .ce-popover-item-html {
            background-color: transparent;
        }

        /* ─── Inline Toolbar (Bold / Italic / Link / etc.) ──────────── */
        .dark .ce-inline-toolbar {
            background-color: #1e293b;
            /* slate-800 */
            border-color: #334155;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        }

        .dark .ce-inline-toolbar__toggler,
        .dark .ce-inline-toolbar__dropdown {
            color: #cbd5e1;
            /* slate-300 */
        }

        .dark .ce-inline-toolbar__toggler:hover,
        .dark .ce-inline-toolbar__dropdown:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        .dark .ce-inline-tool {
            color: #cbd5e1;
            /* slate-300 */
        }

        .dark .ce-inline-tool:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        .dark .ce-inline-tool--active {
            color: #60a5fa;
            /* blue-400 — EditorJS uses blue for active state */
        }

        /* ─── Link Tool Input ───────────────────────────────────────── */
        .dark .ce-inline-tool-input {
            background-color: #0f172a;
            /* slate-900 */
            color: #f1f5f9;
            border-color: #475569;
            /* slate-600 */
        }

        .dark .ce-inline-tool-input--showed {
            border-color: #3b82f6;
            /* blue-500 — mirrors EditorJS active state */
        }

        .dark .ce-inline-tool-input::placeholder {
            color: #475569;
            /* slate-600 */
        }

        /* ─── Block Settings / Tune Menu ────────────────────────────── */
        .dark .ce-settings {
            background-color: #1e293b;
            border-color: #334155;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.45);
        }

        .dark .ce-settings__button {
            color: #cbd5e1;
        }

        .dark .ce-settings__button:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        .dark .ce-settings__button--active {
            color: #60a5fa;
            /* blue-400 */
        }

        /* ─── Loader / Spinner ──────────────────────────────────────── */
        .dark .cdx-loader {
            background-color: #1e293b;
            border-color: #334155;
        }

        .scrollbar-gutter-both {
            scrollbar-gutter: stable both-edges;
        }

        /* ─── Misc ──────────────────────────────────────────────────── */
        @keyframes progress-shrink {
            from {
                width: 100%;
            }

            to {
                width: 0%;
            }
        }
    </style>

    {{-- Auto-Save Splash Notification --}}
    @include('cms::livewire.pages.post.edit-section.auto-save-toast')

    <div class="max-w-7xl mx-auto">
        <div id="formPost" x-data="{
                    postTitle: $wire.entangle('title'),
                    postSlug: $wire.entangle('slug').live,
                    published: $wire.entangle('published').live,
                    showSetting: false,
                    showSeo: false
                }">

            <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
                {{-- LEFT SIDEBAR: Post Metadata --}}
                @include('cms::livewire.pages.post.edit-section.sidebar')

                {{-- RIGHT: EditorJS Content Area --}}
                @include('cms::livewire.pages.post.edit-section.editor')
            </div>
        </div>

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
                                captionPlaceholder: "{{ __('Add image caption...') }}",
                            },
                        },
                        table: {
                            class: Table,
                            inlineToolbar: true,
                            config: {
                                rows: 2,
                                cols: 3,
                            },
                        },
                    },
                    data: data,
                    onChange: async ( api ) =>
                    {
                        const savedData = await api.saver.save();
                        $wire.set( 'content', savedData );
                    },
                    placeholder: "{{ __('Start writing your post content here...') }}"
                } );
            }
        </script>
        @endscript
    </div>
</div>
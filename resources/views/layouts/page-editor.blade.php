<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head
    class="scrollbar-thin scrollbar-thumb-gray-700 scrollbar-track-gray-300 scrollbar-thumb-rounded-full scrollbar-track-rounded-full overscroll-none">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,500;1,500&family=Noto+Color+Emoji&family=Nunito:ital,wght@0,200..1000;1,200..1000&family=Open+Sans:ital,wght@0,500;1,500&family=Quicksand&display=swap"
        rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/image@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/editor.js'])
    @livewireStyles

    <style>
        /* Tombol PLUS */
        .ce-toolbar__plus,
        .ce-toolbar__plus:hover,
        .ce-toolbar__plus:focus {
            background-color: #ffffff !important;
            border-radius: 6px !important;
            color: #000 !important;
        }

        /* Tombol titik 6 (settings / handle) */
        .ce-toolbar__settings-btn,
        .ce-toolbar__settings-btn:hover,
        .ce-toolbar__settings-btn:focus {
            background-color: #ffffff !important;
            border-radius: 6px !important;
            color: #000 !important;
        }

        /* Agar ikon tetap terlihat */
        .ce-toolbar__plus svg,
        .ce-toolbar__settings-btn svg {
            fill: #000 !important;
        }

        /* Hilangkan dark mode override dari EditorJS */
        .dark .ce-toolbar__plus,
        .dark .ce-toolbar__settings-btn {
            background-color: #ffffff !important;
        }

        .ce-block--selected {
            background: #374151 !important;
            /* gray-700 */
            border-radius: 8px;
        }

        /* Dark mode tetap gray-700 */
        .dark .ce-block--selected {
            background: #374151 !important;
        }
    </style>

    {{-- <style>
        /* --- PLUS BUTTON --- */
        .ce-toolbar__plus:hover {
            background: white !important;
            border-radius: 6px !important;
        }

        /* Dark mode */
        .dark .ce-toolbar__plus:hover {
            background: white !important;
        }

        /* --- DRAG HANDLE (titik 6) --- */
        .ce-block__drag:hover {
            background: white !important;
            border-radius: 6px !important;
        }

        /* Dark mode */
        .dark .ce-block__drag:hover {
            background: white !important;
        }

        /* --- SETTINGS BUTTON (tombol titik 3) --- */
        .ce-toolbar__settings-btn:hover {
            background: white !important;
            border-radius: 6px !important;
        }

        /* Dark mode */
        .dark .ce-toolbar__settings-btn:hover {
            background: white !important;
        }

        /* --- BLOCK AKTIF SAAT DI KLIK HANDLE (gray-700) --- */
        .ce-block--selected {
            background: #374151 !important;
            /* gray-700 */
            border-radius: 8px;
        }

        /* Dark mode tetap gray-700 */
        .dark .ce-block--selected {
            background: #374151 !important;
        }
    </style> --}}

</head>

{{-- Layout For Livewire Admin Panel --}}

<body class="min-h-screen bg-gray-100 dark:bg-slate-900">

    <div class="fixed inset-x-0 top-0 z-40 w-full h-full px-4 pb-10 bg-white backdrop-blur-md dark:bg-slate-900 sm:px-6 md:px-8"
        x-data="{ loader: true }" x-show="loader" x-init="setTimeout(() => loader = false, 600)"
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-100">
        <div class="flex items-center justify-center h-screen mx-auto">
            <div class="animate-spin inline-block size-10 border-[3px] border-current border-t-transparent text-gray-400 rounded-full"
                role="status" aria-label="loading">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <span class="sr-only">preloader</span>
    </div>

    <livewire:cms.shared-components.praban-page-editor-topbar />

    <div class="w-full p-0 md:p-4">
        <main>
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
    <x-core::toast />

</body>

</html>
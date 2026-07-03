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
            background-color: rgba(59, 130, 246, 0.6); /* vibrant blue with transparency */
            color: #ffffff;
        }

        .dark .ce-block--selected .ce-block__content {
            background-color: rgba(51, 65, 85, 0.6); /* slate-700 — ensure high visibility */
        }

        .dark .ce-paragraph[data-placeholder]:empty::before {
            color: #475569; /* slate-600 */
        }

        /* ─── Toolbar (+ button & settings ⋮) ─────────────────────── */
        .dark .ce-toolbar__plus,
        .dark .ce-toolbar__settings-btn {
            color: #94a3b8; /* slate-400 */
            background-color: transparent;
        }

        .dark .ce-toolbar__plus:hover,
        .dark .ce-toolbar__settings-btn:hover {
            color: #f1f5f9;
            background-color: #334155; /* slate-700 */
        }

        /* ─── Toolbox Popover (the + menu) ─────────────────────────── */
        .dark .ce-popover,
        .dark .ce-popover--inline {
            background-color: #1e293b; /* slate-800 */
            border-color: #334155;     /* slate-700 */
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
        }

        .dark .ce-popover__container {
            background-color: #1e293b; /* slate-800 */
            border-color: #334155;
        }

        .dark .ce-popover__search .cdx-search-field {
            background-color: #0f172a; /* slate-900 */
            border-color: #334155;
        }

        .dark .ce-popover__search .cdx-search-field__icon {
            color: #64748b; /* slate-500 */
        }

        .dark .ce-popover__search .cdx-search-field__input {
            background-color: transparent;
            color: #f1f5f9;
        }

        .dark .ce-popover__search .cdx-search-field__input::placeholder {
            color: #64748b;
        }

        .dark .ce-popover__nothing-found-message {
            color: #64748b; /* slate-500 */
            background-color: transparent;
        }

        /* ─── Popover Items ─────────────────────────────────────────── */
        .dark .ce-popover-item {
            color: #e2e8f0; /* slate-200 */
        }

        .dark .ce-popover-item:hover,
        .dark .ce-popover-item--focused {
            background-color: #334155; /* slate-700 */
        }

        .dark .ce-popover-item--active {
            background-color: #1e3a5f; /* subtle blue tint, stays readable */
        }

        .dark .ce-popover-item__icon {
            background-color: #334155; /* slate-700 */
            color: #cbd5e1;            /* slate-300 */
            box-shadow: none;
        }

        .dark .ce-popover-item--active .ce-popover-item__icon {
            background-color: #1d4ed8; /* blue-700 */
            color: #fff;
        }

        .dark .ce-popover-item__title {
            color: #e2e8f0; /* slate-200 */
        }

        .dark .ce-popover-item__secondary-title {
            color: #64748b; /* slate-500 — like EditorJS's native muted hint */
        }

        /* HTML wrapper inside popover (link input row) */
        .dark .ce-popover-item-html {
            background-color: transparent;
        }

        /* ─── Inline Toolbar (Bold / Italic / Link / etc.) ──────────── */
        .dark .ce-inline-toolbar {
            background-color: #1e293b; /* slate-800 */
            border-color: #334155;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.5);
        }

        .dark .ce-inline-toolbar__toggler,
        .dark .ce-inline-toolbar__dropdown {
            color: #cbd5e1; /* slate-300 */
        }

        .dark .ce-inline-toolbar__toggler:hover,
        .dark .ce-inline-toolbar__dropdown:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        .dark .ce-inline-tool {
            color: #cbd5e1; /* slate-300 */
        }

        .dark .ce-inline-tool:hover {
            background-color: #334155;
            color: #f1f5f9;
        }

        .dark .ce-inline-tool--active {
            color: #60a5fa; /* blue-400 — EditorJS uses blue for active state */
        }

        /* ─── Link Tool Input ───────────────────────────────────────── */
        .dark .ce-inline-tool-input {
            background-color: #0f172a; /* slate-900 */
            color: #f1f5f9;
            border-color: #475569;     /* slate-600 */
        }

        .dark .ce-inline-tool-input--showed {
            border-color: #3b82f6; /* blue-500 — mirrors EditorJS active state */
        }

        .dark .ce-inline-tool-input::placeholder {
            color: #475569; /* slate-600 */
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
            color: #60a5fa; /* blue-400 */
        }

        /* ─── Loader / Spinner ──────────────────────────────────────── */
        .dark .cdx-loader {
            background-color: #1e293b;
            border-color: #334155;
        }

        /* ─── Misc ──────────────────────────────────────────────────── */
        @keyframes progress-shrink {
            from { width: 100%; }
            to { width: 0%; }
        }
    </style>

    {{-- Auto-Save Splash Notification --}}
    <div x-data="{ showAutoSaveSplash: true }" 
         x-init="setTimeout(() => showAutoSaveSplash = false, 5000)"
         x-show="showAutoSaveSplash"
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="opacity-0 transform translate-x-12"
         x-transition:enter-end="opacity-100 transform translate-x-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         class="fixed bottom-6 right-6 z-60 w-full max-w-sm"
         style="display: none">
        <div class="relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-emerald-200 dark:border-emerald-800/50 rounded-2xl shadow-2xl p-5 group ring-1 ring-black/5 dark:ring-white/5">
            {{-- Close Button --}}
            <button @click="showAutoSaveSplash = false" class="absolute top-3 right-3 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-all">
                <x-lucide-x class="w-4 h-4" />
            </button>

            <div class="flex items-start gap-4">
                <div class="p-3 bg-linear-to-br from-emerald-500 to-teal-600 text-white rounded-xl shadow-lg shadow-emerald-500/20">
                    <x-lucide-zap class="w-6 h-6 animate-pulse" />
                </div>
                <div class="flex-1 pr-4">
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                        {{ __('Auto-Save Aktif!') }}
                        <span class="px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-[10px] text-emerald-700 dark:text-emerald-400 uppercase tracking-wider font-extrabold rounded-md">{{ __('New') }}</span>
                    </h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        {{ __('Setiap perubahan Anda kini disimpan secara otomatis. Tidak perlu lagi menekan tombol simpan manual.') }}
                    </p>
                </div>
            </div>

            {{-- Countdown Progress Bar --}}
            <div class="absolute bottom-0 left-0 h-1 bg-emerald-500/20 w-full">
                <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] animate-[progress-shrink_5s_linear_forwards]"></div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto">
        {{-- Help Guide --}}
        <div
            class="mb-6 p-5 bg-linear-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/20 dark:to-teal-900/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-emerald-600 rounded-xl shadow-lg">
                    <x-lucide-pen-tool class="w-6 h-6 text-white" />
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">{{ __('Page Editor Guide') }}</h3>
                    <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                        {{ __('Edit your page content using the powerful Bale Editor. Fill in the metadata on the left, then create your content on the right.') }}
                    </p>
                    <div class="grid gap-2 md:grid-cols-2">
                        <div class="flex items-start gap-2">
                            <x-lucide-check class="w-4 h-4 text-emerald-600 mt-0.5" />
                            <span
                                class="text-sm text-gray-600 dark:text-gray-400">{{ __('Everything saves automatically') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="formPage" x-data="{ 
                pageTitle: $wire.entangle('title'), 
                pageSlug: $wire.entangle('slug').live, 
                showSetting: $wire.entangle('show_setting'),
                showSeo: $wire.entangle('showSeo')
            }">

            <div class="grid grid-cols-1 lg:grid-cols-7 gap-6">
                {{-- LEFT SIDEBAR: Page Metadata (Sticky) --}}
                <div class="lg:col-span-2">
                    <div
                        class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 lg:sticky lg:top-24 space-y-6 max-h-[calc(100vh-7rem)] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                        {{-- Header --}}
                        <div class="flex items-center gap-3 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <div class="p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md">
                                <x-lucide-file-text class="w-5 h-5 text-white" />
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Page Details') }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Metadata & settings') }}</p>
                            </div>
                        </div>

                        {{-- Page Title --}}
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-type class="w-4 h-4 text-gray-400" />
                                <label
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Page Title') }}
                                    *</label>
                            </div>
                            <x-core::input wire:model.blur='title' placeholder="{{ __('Enter page title...') }}"
                                x-model="pageTitle" />
                            <x-core::input-error for="title" />
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ __('Main title for your page') }}
                            </p>
                        </div>

                        {{-- Advanced Settings Toggle --}}
                        <button
                            class="flex items-center gap-2 w-full p-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-900/50 dark:hover:bg-gray-900 rounded-lg transition-colors"
                            type="button" @click="showSetting=!showSetting"
                            :class="showSetting ? 'ring-2 ring-emerald-500 dark:ring-emerald-400' : ''">
                            <x-lucide-settings class="w-4 h-4 text-gray-400" />
                            <span class="flex-1 text-left text-sm font-medium"
                                :class="showSetting ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-700 dark:text-gray-300'">
                                {{ __('Advanced Settings') }}
                            </span>
                            <x-lucide-chevron-down class="w-4 h-4 transition-transform" />
                        </button>

                        {{-- Page Slug (Collapsed) --}}
                        <div x-show="showSetting" x-collapse>
                            <div
                                class="p-4 bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800 rounded-xl space-y-3">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-lucide-link class="w-4 h-4 text-emerald-600" />
                                    <label
                                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Permalink / Slug') }}</label>
                                    <div class="hs-tooltip">
                                        <button type="button" class="hs-tooltip-toggle">
                                            <x-lucide-info class="w-3.5 h-3.5 text-emerald-600" />
                                        </button>
                                        <span
                                            class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-2 px-3 bg-gray-900 text-xs text-white rounded-lg shadow-lg dark:bg-gray-700"
                                            role="tooltip">
                                            {{ __('Permalink is the permanent URL for this page') }}
                                        </span>
                                    </div>
                                </div>
                                <x-core::input wire:model.blur='slug' name="slug" x-slug="pageTitle" x-model="pageSlug"
                                    placeholder="{{ __('auto-generated-from-title') }}" />
                                <x-core::input-error for="slug" />
                                <p class="text-xs text-emerald-700 dark:text-emerald-400">
                                    {{ __('Auto-generated from title. Customize if needed.') }}
                                </p>
                            </div>
                        </div>

                        {{-- SEO Configuration Toggle --}}
                        @can('bale-seo.update')
                        <button
                            class="flex items-center gap-2 w-full p-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-900/50 dark:hover:bg-gray-900 rounded-lg transition-colors"
                            type="button" @click="showSeo=!showSeo"
                            :class="showSeo ? 'ring-2 ring-purple-500 dark:ring-purple-400' : ''">
                            <x-lucide-search class="w-4 h-4 text-gray-400" />
                            <span class="flex-1 text-left text-sm font-medium"
                                :class="showSeo ? 'text-purple-600 dark:text-purple-400' : 'text-gray-700 dark:text-gray-300'">
                                {{ __('SEO Configuration') }}
                            </span>
                            <x-lucide-chevron-down class="w-4 h-4 transition-transform" />
                        </button>

                        {{-- SEO Configuration (Collapsed) --}}
                        <div x-show="showSeo" x-collapse>
                            <div class="p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl space-y-5">
                                {{-- Meta Title --}}
                                <div>
                                    <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-2">{{ __('Meta Title') }}</label>
                                    <x-core::input wire:model.blur="seo_title" placeholder="{{ __('Fallback to page title') }}" />
                                    <p class="mt-1 text-[10px] text-gray-500">{{ __('Search engine title. Recommended < 60 chars.') }}</p>
                                </div>

                                {{-- Meta Description --}}
                                <div x-data="{ count: $wire.entangle('seo_description').live ? $wire.entangle('seo_description').live.length : 0 }">
                                    <div class="flex justify-between mb-2">
                                        <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider">{{ __('Meta Description') }}</label>
                                        <span class="text-[10px] font-mono" :class="count > 160 ? 'text-red-500' : 'text-gray-400'"><span x-text="count"></span>/160</span>
                                    </div>
                                    <textarea wire:model.blur="seo_description" x-on:input="count = $event.target.value.length"
                                        class="w-full text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-purple-500 focus:border-purple-500 transition-all"
                                        rows="3" placeholder="{{ __('Brief summary for search results...') }}"></textarea>
                                </div>

                                {{-- Meta Keywords --}}
                                <div>
                                    <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-2">{{ __('Keywords') }}</label>
                                    <x-core::input wire:model.blur="seo_keywords" placeholder="{{ __('e.g. tech, news, bale') }}" />
                                </div>

                                {{-- OG Image --}}
                                <div>
                                    <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-2">{{ __('Social Share Image (OG)') }}</label>
                                    
                                    @if ($og_image)
                                        <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 mb-2">
                                            <img src="{{ \Bale\Core\Support\Cdn::url('thumbnails/' . $og_image) }}" class="w-full h-24 object-cover">
                                            <button wire:click="deleteOgImage" type="button" class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    @endif

                                    <x-core::upload-zone wire:model.live="og_image_new" accept="image/*" maxSize="1024" :label="__('Custom social image')" />
                                    <p class="mt-1 text-[10px] text-gray-500">{{ __('Optional custom image for social sharing.') }}</p>
                                    <x-core::input-error for="og_image_new" class="mt-1" />
                                </div>

                                {{-- Twitter Card --}}
                                <div>
                                    <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-2">{{ __('Twitter Card type') }}</label>
                                    <select wire:model.live="twitter_card" class="w-full text-sm bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 rounded-xl focus:ring-purple-500 focus:border-purple-500">
                                        <option value="summary">{{ __('Summary') }}</option>
                                        <option value="summary_large_image">{{ __('Summary Large Image') }}</option>
                                    </select>
                                </div>

                                {{-- Index Controls --}}
                                <div class="space-y-3 pt-2">
                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-eye-off class="w-4 h-4 text-gray-400" />
                                            <span class="text-xs font-medium">{{ __('No Index') }}</span>
                                        </div>
                                        <label class="relative inline-block w-10 h-5 cursor-pointer">
                                            <input type="checkbox" wire:model.live="no_index" class="peer sr-only">
                                            <span class="absolute inset-0 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors peer-checked:bg-purple-600"></span>
                                            <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></span>
                                        </label>
                                    </div>

                                    <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center gap-2">
                                            <x-lucide-link-2-off class="w-4 h-4 text-gray-400" />
                                            <span class="text-xs font-medium">{{ __('No Follow') }}</span>
                                        </div>
                                        <label class="relative inline-block w-10 h-5 cursor-pointer">
                                            <input type="checkbox" wire:model.live="no_follow" class="peer sr-only">
                                            <span class="absolute inset-0 bg-gray-200 dark:bg-gray-700 rounded-full transition-colors peer-checked:bg-purple-600"></span>
                                            <span class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5 shadow-sm"></span>
                                        </label>
                                    </div>
                                </div>

                                {{-- Canonical & Structured Data --}}
                                <div x-data="{ openAdvanced: false }">
                                    <button type="button" @click="openAdvanced = !openAdvanced" class="text-[10px] text-purple-600 dark:text-purple-400 font-bold uppercase tracking-widest flex items-center gap-1 hover:underline">
                                        <x-lucide-plus class="w-3 h-3" /> {{ __('Advanced SEO') }}
                                    </button>
                                    <div x-show="openAdvanced" x-collapse class="mt-4 space-y-4 pt-2 border-t border-purple-200/50">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Canonical URL') }}</label>
                                            <x-core::input wire:model.blur="canonical_url" placeholder="https://example.com/original-page" />
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Structured Data (JSON-LD)') }}</label>
                                            <textarea wire:model.blur="structured_data"
                                                class="w-full text-[10px] font-mono bg-gray-900 text-emerald-400 border-gray-700 rounded-lg focus:ring-purple-500 focus:border-purple-500"
                                                rows="5" placeholder='{ "@@context": "https://schema.org", ... }'></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endcan

                    </div>
                </div>

                {{-- RIGHT: EditorJS Content Area --}}
                <div class="lg:col-span-5">
                    <div
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden">
                        {{-- Editor Header --}}
                        <div
                            class="flex items-center justify-between px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-emerald-50 to-teal-50 dark:from-emerald-900/10 dark:to-teal-900/10 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-2 md:gap-3">
                                <div
                                    class="p-2 md:p-2.5 bg-linear-to-br from-emerald-500 to-emerald-600 rounded-lg shadow-md">
                                    <x-lucide-file-edit class="w-4 md:w-5 h-4 md:h-5 text-white" />
                                </div>
                                <div>
                                    <h3 class="font-bold text-lg text-gray-900 dark:text-white">
                                        {{ __('Content Editor') }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ __('Write your page content') }}</p>
                                </div>
                            </div>
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-green-100 dark:bg-green-900/30 border border-green-300 dark:border-green-700 rounded-full">
                                <div class="w-2 h-2 bg-green-600 rounded-full animate-pulse"></div>
                                <span
                                    class="text-xs font-medium text-green-700 dark:text-green-400">{{ __('Dynamic Saving') }}</span>
                            </div>
                        </div>

                        {{-- Editor Toolbar Guide --}}
                        <div
                            class="px-6 py-3 bg-emerald-50 dark:bg-emerald-900/10 border-b border-emerald-200 dark:border-emerald-800/50">
                            <div class="flex items-start gap-2 text-xs text-emerald-700 dark:text-emerald-400">
                                <x-lucide-lightbulb class="w-4 h-4 mt-0.5" />
                                <div>
                                    <span class="font-semibold">{{ __('Quick tip:') }}</span>
                                    <span>{{ __('Press') }} <kbd
                                            class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-emerald-300 rounded text-emerald-800 dark:text-emerald-300 font-mono">{{ __('Content Editor') }}</kbd>
                                        {{ __('to start typing, and use') }} <kbd
                                            class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-emerald-300 rounded text-emerald-800 dark:text-emerald-300 font-mono">+</kbd>
                                        {{ __('button on the left.') }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- EditorJS Container --}}
                        <div wire:ignore id="editorjs"
                            class="px-6 py-8 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 min-h-[70vh] max-h-[70vh] overflow-y-auto prose prose-slate dark:prose-invert max-w-none
                        scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">
                        </div>

                        <x-core::input-error for="content" />

                        {{-- Editor Footer --}}
                        <div
                            class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-4">
                                    <div class="flex items-center gap-1.5">
                                        <x-lucide-text class="w-3.5 h-3.5" />
                                        <span>{{ __('Editor') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <x-lucide-image class="w-3.5 h-3.5" />
                                        <span>{{ __('Image support') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <x-lucide-list class="w-3.5 h-3.5" />
                                        <span>{{ __('Lists') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <x-lucide-table class="w-3.5 h-3.5" />
                                        <span>{{ __('Tables') }}</span>
                                    </div>
                                </div>
                                <span class="text-gray-500">{{ __('Last edited:') }}
                                    {{ \Carbon\Carbon::parse($updated_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                            field: 'image',
                            types: 'image/*',
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
                placeholder: "{{ __('Start writing your page content here...') }}"
            } );
        }
    </script>
    @endscript
</div>

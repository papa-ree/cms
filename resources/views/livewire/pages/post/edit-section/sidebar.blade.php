{{-- LEFT SIDEBAR: Post Metadata --}}
<div class="lg:col-span-2">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 lg:sticky lg:top-24 lg:max-h-[calc(100vh-8rem)] flex flex-col overflow-hidden">
        {{-- Header (aligned with editor header) --}}
        <div class="flex items-center gap-3 px-4 md:px-6 py-3 md:py-4 border-b border-gray-200 dark:border-gray-700 shrink-0">
            <div class="p-2 md:p-2.5 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-md">
                <x-lucide-file-text class="w-4 md:w-5 h-4 md:h-5 text-white" />
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ __('Post Details') }}</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('Metadata & settings') }}</p>
            </div>
        </div>

        {{-- Scrollable content --}}
        <div class="flex-1 overflow-auto scrollbar-gutter-both p-6 space-y-6 scrollbar-thin scrollbar-thumb-gray-400 dark:scrollbar-thumb-gray-600 scrollbar-track-gray-200 dark:scrollbar-track-gray-800 scrollbar-thumb-rounded-full">

            {{-- Post Title --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <x-lucide-type class="w-4 h-4 text-gray-400" />
                    <label
                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Post Title') }}
                        *</label>
                </div>
                <x-core::input wire:model.blur='title' placeholder="{{ __('Enter post title...') }}"
                    x-model="postTitle" />
                <x-core::input-error for="title" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ __('Main title for your post') }}
                </p>
            </div>

            {{-- Advanced Settings Toggle --}}
            <button
                class="flex items-center gap-2 w-full p-3 bg-gray-50 hover:bg-gray-100 dark:bg-gray-900/50 dark:hover:bg-gray-900 rounded-lg transition-colors"
                type="button" @click="showSetting=!showSetting"
                :class="showSetting ? 'ring-2 ring-blue-500 dark:ring-blue-400' : ''">
                <x-lucide-settings class="w-4 h-4 text-gray-400" />
                <span class="flex-1 text-left text-sm font-medium"
                    :class="showSetting ? 'text-blue-600 dark:text-blue-400' : 'text-gray-700 dark:text-gray-300'">
                    {{ __('Advanced Settings') }}
                </span>
                <x-lucide-chevron-down class="w-4 h-4 transition-transform dark:text-white" />
            </button>

            {{-- Post Slug (Collapsed) --}}
            <div x-show="showSetting" x-collapse>
                <div
                    class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl space-y-3">
                    <div class="flex items-center gap-2 mb-2">
                        <x-lucide-link class="w-4 h-4 text-blue-600" />
                        <label
                            class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Permalink / Slug') }}</label>
                        <div class="hs-tooltip">
                            <button type="button" class="hs-tooltip-toggle">
                                <x-lucide-info class="w-3.5 h-3.5 text-blue-600" />
                            </button>
                            <span
                                class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-2 px-3 bg-gray-900 text-xs text-white rounded-lg shadow-lg dark:bg-gray-700"
                                role="tooltip">
                                {{ __('Permalink is the permanent URL for this post') }}
                            </span>
                        </div>
                    </div>
                    <x-core::input wire:model.blur='slug' name="slug" x-slug="postTitle" x-model="postSlug"
                        placeholder="{{ __('auto-generated-from-title') }}" />
                    <x-core::input-error for="slug" />
                    <p class="text-xs text-blue-700 dark:text-blue-400">
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
                <x-lucide-chevron-down class="w-4 h-4 transition-transform dark:text-white" />
            </button>

            {{-- SEO Configuration (Collapsed) --}}
            <div x-show="showSeo" x-collapse>
                <div class="p-4 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-xl space-y-5">
                    {{-- Meta Title --}}
                    <div>
                        <label class="block text-xs font-bold text-purple-700 dark:text-purple-400 uppercase tracking-wider mb-2">{{ __('Meta Title') }}</label>
                        <x-core::input wire:model.blur="seo_title" placeholder="{{ __('Fallback to main title') }}" />
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
                        <p class="mt-1 text-[10px] text-gray-500">{{ __('Fallback to featured image if empty.') }}</p>
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
                            <x-lucide-plus class="w-3" /> {{ __('Advanced SEO') }}
                        </button>
                        <div x-show="openAdvanced" x-collapse class="mt-4 space-y-4 pt-2 border-t border-purple-200/50">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ __('Canonical URL') }}</label>
                                <x-core::input wire:model.blur="canonical_url" placeholder="https://example.com/original-post" />
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

            {{-- Publish Toggle --}}
            <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-1.5 bg-emerald-100 dark:bg-emerald-900/40 rounded-lg" x-show="published">
                        <x-lucide-radio class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                    </div>
                    <div class="p-1.5 bg-amber-100 dark:bg-amber-900/40 rounded-lg" x-show="!published">
                        <x-lucide-pencil-line class="w-4 h-4 text-amber-600 dark:text-amber-400" />
                    </div>
                    <div>
                        <label for="post-published-toggle"
                            class="text-sm font-semibold text-gray-900 dark:text-white cursor-pointer">
                            {{ __('Publish Post') }}
                        </label>
                        <p class="text-xs mt-0.5">
                            <span class="text-emerald-600 dark:text-emerald-400 font-medium" x-show="published">{{ __('Live — visible to readers') }}</span>
                            <span class="text-amber-600 dark:text-amber-400 font-medium" x-show="!published">{{ __('Draft — not yet published') }}</span>
                        </p>
                    </div>
                </div>
                <label for="post-published-toggle"
                    class="relative inline-block w-12 h-6 cursor-pointer shrink-0">
                    <input type="checkbox" id="post-published-toggle"
                        x-model="published"
                        class="peer sr-only">
                    <span class="absolute inset-0 bg-gray-300 dark:bg-gray-700 rounded-full transition-colors peer-checked:bg-emerald-600 dark:peer-checked:bg-emerald-500"></span>
                    <span class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-transform peer-checked:translate-x-6"></span>
                </label>
            </div>

            {{-- Post Category --}}
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <x-lucide-layers class="w-4 h-4 text-gray-400" />
                    <label
                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Category') }}</label>
                </div>
                <x-core::select-dropdown :label="__('Select Category')" :items="$this->categories"
                    model="category_slug" labelField="name" valueField="slug" />
                <x-core::input-error for="category_slug" />
            </div>

            {{-- Post Thumbnail --}}
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <x-lucide-image class="w-4 h-4 text-gray-400" />
                    <label
                        class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Featured Image') }}</label>
                </div>

                {{-- Existing saved thumbnail --}}
                @if ($thumbnail)
                    <div
                        class="relative group overflow-hidden rounded-xl border-2 border-gray-200 dark:border-gray-700 hover:border-red-400 dark:hover:border-red-600 transition-all mb-3">
                        <img loading="lazy"
                            class="w-full h-40 object-cover object-center group-hover:scale-105 transition-transform duration-300"
                            src="{{ \Bale\Core\Support\Cdn::url('thumbnails/' . $thumbnail) }}"
                            alt="{{ $title }}">
                        <div
                            class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity hidden lg:block">
                            <button wire:click='deleteThumbnail' type="button"
                                class="absolute top-3 right-3 p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-lg transition-colors">
                                <x-lucide-trash-2 class="w-4 h-4" />
                            </button>
                        </div>

                        {{-- Mobile overlay button --}}
                        <div class="absolute top-2 right-2 lg:hidden z-10 transition-all">
                            <button wire:click='deleteThumbnail' type="button"
                                class="p-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg shadow-lg transition-colors">
                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                            </button>
                        </div>
                        <div
                            class="absolute bottom-2 left-2 px-2 py-0.5 rounded-full bg-black/50 text-white text-[10px] font-medium">
                            {{ __('Current thumbnail') }}
                        </div>
                    </div>
                @endif

                {{-- Upload new thumbnail --}}
                @if (!$thumbnail)
                    <x-core::upload-zone wire:model.live="thumbnail_new" accept="image/png,image/jpeg,image/jpg"
                        maxSize="512" :label="__('Drop image here or click to browse')" :hint="__('PNG, JPG, JPEG · Max 512KB')" />
                @endif

                <x-core::input-error for="thumbnail_new" class="mt-1" />
            </div>
        </div>
    </div>
</div>

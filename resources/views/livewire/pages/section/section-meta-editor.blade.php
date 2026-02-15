<div>
    <x-core::breadcrumb :items="[['label' => 'Sections', 'route' => 'bale.cms.sections.index']]" :active="$name" />

    {{-- Hero Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">

        {{-- Decorative Elements --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div>
                    {{-- Section Name + Status --}}
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                            <x-lucide-layout class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white">{{ $name }}</h1>
                            <span
                                class="inline-flex items-center gap-1.5 mt-1 px-3 py-1 rounded-full text-xs font-medium {{ $actived ? 'bg-emerald-500/20 text-emerald-100' : 'bg-gray-500/20 text-gray-100' }}">
                                <span
                                    class="w-1.5 h-1.5 rounded-full {{ $actived ? 'bg-emerald-300' : 'bg-gray-300' }}"></span>
                                {{ $actived ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    {{-- Description --}}
                    <p class="text-white/90 text-lg max-w-2xl">
                        Customize the appearance and content of this section. All changes are saved automatically.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 justify-center">
                    <a href="{{ url('/') }}#{{ $slug }}" target="_blank"
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-external-link class="w-4 h-4 hidden lg:block" />
                        Preview
                    </a>
                    <a href="{{ route('bale.cms.sections.view-searchable', $slug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-database class="w-4 h-4 hidden lg:block" />
                        Data Items
                    </a>
                    <button wire:click="toggleActive"
                        class="inline-flex items-center gap-2 px-4 py-2.5 {{ $actived ? 'bg-white text-purple-600 hover:bg-white/90' : 'bg-emerald-500 text-white hover:bg-emerald-600' }} rounded-lg text-sm font-medium transition-all shadow-lg">
                        <x-lucide-power class="w-4 h-4 hidden lg:block" />
                        {{ $actived ? 'Deactivate' : 'Activate' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Area --}}
    <form x-data="sectionEditor" class="grid grid-cols-1 lg:grid-cols-4 gap-8" @submit.prevent>

        {{-- Sidebar Navigation --}}
        <div class="lg:col-span-1 hidden lg:block">
            <div class="sticky top-24 space-y-6">
                {{-- Quick Navigation --}}
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-slate-700">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <x-lucide-navigation class="w-4 h-4 text-purple-600" />
                        Quick Jump
                    </h3>
                    <nav class="space-y-1">
                        <a href="#basic-info"
                            class="block px-3 py-2 text-sm rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            Basic Info
                        </a>
                        <a href="#buttons"
                            class="block px-3 py-2 text-sm rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            Action Buttons
                        </a>
                        <a href="#background"
                            class="block px-3 py-2 text-sm rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            Background
                        </a>
                        <a href="#advanced"
                            class="block px-3 py-2 text-sm rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                            Advanced
                        </a>
                    </nav>
                </div>

                {{-- Tips Card --}}
                <div
                    class="bg-linear-to-br from-purple-50 to-indigo-50 dark:from-purple-900/20 dark:to-indigo-900/20 rounded-2xl p-6 border border-purple-100 dark:border-purple-800">
                    <h3 class="font-semibold text-purple-900 dark:text-purple-300 mb-3 flex items-center gap-2">
                        <x-lucide-lightbulb class="w-4 h-4" />
                        Editor Tips
                    </h3>
                    <ul class="space-y-3 text-sm text-purple-800 dark:text-purple-400">
                        <li class="flex gap-2">
                            <x-lucide-check-circle class="w-4 h-4 mt-0.5 shrink-0" />
                            <span>Changes save automatically as you type</span>
                        </li>
                        <li class="flex gap-2">
                            <x-lucide-image class="w-4 h-4 mt-0.5 shrink-0" />
                            <span>Upload high-quality images for best results</span>
                        </li>
                        <li class="flex gap-2">
                            <x-lucide-eye class="w-4 h-4 mt-0.5 shrink-0" />
                            <span>Use preview to see changes live</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Main Content Column --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- 1. BASIC INFO CARD --}}
            <div id="basic-info"
                class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-slate-700">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                            <x-lucide-type class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Basic Information
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Title and subtitle for this section</p>
                        </div>
                    </div>

                    {{-- Save Status --}}
                    <div class="save-status">
                        <template x-if="states.title === 'saving' || states.subtitle === 'saving'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Saving...</span>
                            </div>
                        </template>
                        <template
                            x-if="(states.title === 'saved' || states.subtitle === 'saved') && states.title !== 'saving' && states.subtitle !== 'saving'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                                <x-lucide-check class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Saved</span>
                            </div>
                        </template>
                        <template x-if="states.title === 'error' || states.subtitle === 'error'">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full">
                                <x-lucide-alert-circle class="w-3 h-3 text-red-600 dark:text-red-400" />
                                <span class="text-xs font-medium text-red-700 dark:text-red-400">Error</span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Fields --}}
                <div class="space-y-6">
                    <x-core::input label="Section Title" x-model="data.title" @input.debounce.1000ms="save('title')"
                        placeholder="e.g. Welcome to Our Platform" />

                    <div>
                        <x-core::label value="Subtitle / Description" />
                        <textarea x-model="data.subtitle" @input.debounce.1000ms="save('subtitle')" rows="3"
                            class="block w-full py-3 px-4 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 form-input dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent resize-none"
                            placeholder="Add a brief description or subtitle..."></textarea>
                    </div>
                </div>
            </div>

            {{-- 2. BUTTONS CARD --}}
            <div id="buttons"
                class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-slate-700">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <x-lucide-mouse-pointer-click class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Action Buttons
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="data.buttons.length"></span> button<span
                                    x-show="data.buttons.length !== 1">s</span> configured
                            </p>
                        </div>
                    </div>

                    {{-- Save Status --}}
                    <div class="save-status">
                        <template x-if="states.buttons === 'saving'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Saving...</span>
                            </div>
                        </template>
                        <template x-if="states.buttons === 'saved'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                                <x-lucide-check class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Saved</span>
                            </div>
                        </template>
                        <template x-if="states.buttons === 'error'">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full">
                                <x-lucide-alert-circle class="w-3 h-3 text-red-600 dark:text-red-400" />
                                <span class="text-xs font-medium text-red-700 dark:text-red-400">Error</span>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Button List --}}
                <div class="space-y-4">
                    <template x-for="(button, index) in data.buttons" :key="index">
                        <div
                            class="group relative bg-linear-to-br from-gray-50 to-gray-100/50 dark:from-slate-900 dark:to-slate-800/50 p-6 rounded-xl border border-gray-200 dark:border-slate-700 hover:border-purple-300 dark:hover:border-purple-700 transition-all">

                            {{-- Button Number Badge --}}
                            <div
                                class="absolute -top-3 -left-3 w-8 h-8 bg-linear-to-br from-purple-600 to-indigo-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                                <span x-text="index + 1"></span>
                            </div>

                            {{-- Fields --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pl-2">
                                {{-- Label --}}
                                <div>
                                    <x-core::input label="Button Label" x-model="button.label"
                                        @input.debounce.1000ms="save('buttons')" placeholder="Button Text" />
                                </div>

                                {{-- URL --}}
                                <div>
                                    <x-core::input label="URL / Link" x-model="button.url"
                                        @input.debounce.1000ms="save('buttons')" placeholder="https:// or /path" />
                                </div>

                                {{-- Icon --}}
                                <div class="md:col-span-2">
                                    <div class="flex items-end gap-2">
                                        <div class="flex-1">
                                            <x-core::input label="Icon (Optional)" x-model="button.icon"
                                                @input.debounce.1000ms="save('buttons')"
                                                placeholder="e.g. arrow-right, check, star" />
                                        </div>
                                        <a href="https://lucide.dev/icons" target="_blank"
                                            class="p-3 text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 rounded-xl transition-colors border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 mb-0.5"
                                            title="Browse Icons">
                                            <x-lucide-external-link class="w-5 h-5" />
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Actions --}}
                            <div
                                class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-slate-700">
                                <label class="inline-flex items-center cursor-pointer group/toggle">
                                    <input type="checkbox" x-model="button.show" @change="save('buttons')"
                                        class="sr-only peer">
                                    <div
                                        class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600">
                                    </div>
                                    <span class="ms-3 text-sm font-medium text-gray-700 dark:text-gray-300">Show
                                        button</span>
                                </label>

                                <button type="button" @click="removeButton(index)"
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                    <x-lucide-trash-2 class="w-4 h-4" />
                                    Remove
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Add Button --}}
                    <button type="button" @click="addButton"
                        class="w-full py-4 border-2 border-dashed border-gray-300 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-600 dark:text-gray-400 hover:border-purple-500 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/10 transition-all flex items-center justify-center gap-2 group">
                        <div
                            class="p-1.5 bg-gray-100 dark:bg-slate-700 rounded-lg group-hover:bg-purple-100 dark:group-hover:bg-purple-900/30 transition-colors">
                            <x-lucide-plus class="w-4 h-4" />
                        </div>
                        Add New Button
                    </button>
                </div>
            </div>

            {{-- 3. BACKGROUND CARD --}}
            <div id="background"
                class="bg-white dark:bg-slate-800 rounded-2xl p-8 shadow-sm border border-gray-100 dark:border-slate-700">
                {{-- Header --}}
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl">
                            <x-lucide-image class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                        </div>
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Background
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Configure section background images</p>
                        </div>
                    </div>

                    {{-- Save Status --}}
                    <div class="save-status">
                        <template x-if="states.background === 'saving'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-400">Saving...</span>
                            </div>
                        </template>
                        <template x-if="states.background === 'saved'">
                            <div
                                class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                                <x-lucide-check class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Saved</span>
                            </div>
                        </template>
                        <template x-if="states.background === 'error'">
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full">
                                <x-lucide-alert-circle class="w-3 h-3 text-red-600 dark:text-red-400" />
                                <span class="text-xs font-medium text-red-700 dark:text-red-400">Error</span>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="space-y-6">
                    {{-- Type Selector --}}
                    <div>
                        <x-core::label value="Background Type" />
                        <select x-model="data.backgroundType" @change="save('background')"
                            class="block w-full py-3 px-4 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 form-input dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="image">Static Image</option>
                            <option value="slider">Image Slider</option>
                        </select>
                    </div>

                    {{-- Image Upload Area --}}
                    <template x-if="data.backgroundType === 'image' || data.backgroundType === 'slider'">
                        <div class="space-y-4">
                            {{-- Image Grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4"
                                x-show="data.backgroundImages.length > 0">
                                <template x-for="(img, index) in data.backgroundImages" :key="index">
                                    <div
                                        class="relative group aspect-square bg-gray-100 dark:bg-slate-900 rounded-xl overflow-hidden border-2 border-gray-200 dark:border-slate-700 hover:border-purple-400 dark:hover:border-purple-600 transition-all">
                                        <img :src="img.cdn_url || img.url" alt="Background"
                                            class="w-full h-full object-cover"
                                            onerror="this.src='data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22%3E%3Crect fill=%22%23ddd%22 width=%22200%22 height=%22200%22/%3E%3Ctext fill=%22%23999%22 x=%2250%25%22 y=%2250%25%22 dominant-baseline=%22middle%22 text-anchor=%22middle%22%3EImage%3C/text%3E%3C/svg%3E'">

                                        <div
                                            class="absolute inset-0 bg-linear-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center pb-3">
                                            <button type="button" @click="confirmDelete(index)"
                                                class="px-3 py-1.5 bg-white text-red-600 rounded-lg shadow-lg hover:bg-red-50 transition-colors text-sm font-medium flex items-center gap-1.5">
                                                <x-lucide-trash-2 class="w-3.5 h-3.5" />
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Upload Dropzone --}}
                            <div x-show="data.backgroundType === 'slider' || data.backgroundImages.length === 0"
                                class="mt-4">
                                <x-cms::filepond wire:model.live="background_new" allowImagePreview
                                    imagePreviewMaxHeight="200" allowFileTypeValidation
                                    acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']" allowFileSizeValidation
                                    maxFileSize="512kb" :allowMultiple="$backgroundType === 'slider'" />

                                <x-core::input-error for="background_new" />
                                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                    Max 512KB. Formats: PNG, JPG, JPEG
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- 4. ADVANCED SETTINGS (Schema-based Custom Fields) --}}
            @if(!empty($this->customFieldsConfig))
                <div id="advanced" x-data="{ open: false }"
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700 overflow-hidden">
                    <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between p-8 hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                                <x-lucide-settings class="w-5 h-5 text-amber-600 dark:text-amber-400" />
                            </div>
                            <div class="text-left">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Advanced Settings
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Additional configuration options for
                                    this section type</p>
                            </div>
                        </div>
                        <x-lucide-chevron-down class="w-5 h-5 text-gray-400 transition-transform duration-200"
                            x-bind:class="open ? 'rotate-180' : ''" />
                    </button>

                    <div x-show="open" x-collapse>
                        <div class="px-8 pb-8 pt-4 border-t border-gray-100 dark:border-slate-700">
                            <div class="space-y-6">
                                @foreach($this->customFieldsConfig as $key => $config)
                                    <div>
                                        @if($config['type'] === 'string')
                                            {{-- String Input --}}
                                            <x-core::input :label="$config['label']" x-model="data.custom.{{ $key }}"
                                                @input.debounce.1000ms="save('custom')"
                                                placeholder="{{ $config['default'] ?? '' }}" />

                                        @elseif($config['type'] === 'integer')
                                            {{-- Integer Input --}}
                                            <x-core::input type="number" :label="$config['label']" x-model="data.custom.{{ $key }}"
                                                @input.debounce.1000ms="save('custom')"
                                                placeholder="{{ $config['default'] ?? '' }}" />

                                        @elseif($config['type'] === 'boolean')
                                            {{-- Boolean Toggle --}}
                                            <div
                                                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-slate-700">
                                                <label class="text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                                    {{ $config['label'] }}
                                                </label>
                                                <label class="relative inline-block w-12 h-6 cursor-pointer">
                                                    <input type="checkbox" x-model="data.custom.{{ $key }}" @change="save('custom')"
                                                        class="peer sr-only">
                                                    <span
                                                        class="absolute inset-0 bg-gray-300 rounded-full transition peer-checked:bg-purple-600 dark:bg-gray-700 dark:peer-checked:bg-purple-500"></span>
                                                    <span
                                                        class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition peer-checked:translate-x-6"></span>
                                                </label>
                                            </div>

                                        @elseif($config['type'] === 'enum')
                                            {{-- Enum Select --}}
                                            <div>
                                                <x-core::label :value="$config['label']" />
                                                <select x-model="data.custom.{{ $key }}" @change="save('custom')"
                                                    class="block w-full py-3 px-4 text-gray-900 placeholder-gray-500 transition-all duration-200 bg-white border border-gray-300 form-input dark:border-gray-600 rounded-xl dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                                    @foreach($config['options'] as $option)
                                                        <option value="{{ $option }}">{{ ucfirst($option) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach

                                {{-- Save Status --}}
                                <div class="flex justify-end">
                                    <template x-if="states.custom === 'saving'">
                                        <div
                                            class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                                            <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                            <span
                                                class="text-xs font-medium text-blue-700 dark:text-blue-400">Saving...</span>
                                        </div>
                                    </template>
                                    <template x-if="states.custom === 'saved'">
                                        <div
                                            class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-900/20 rounded-full">
                                            <x-lucide-check class="w-3 h-3 text-emerald-600 dark:text-emerald-400" />
                                            <span
                                                class="text-xs font-medium text-emerald-700 dark:text-emerald-400">Saved</span>
                                        </div>
                                    </template>
                                    <template x-if="states.custom === 'error'">
                                        <div
                                            class="flex items-center gap-2 px-3 py-1.5 bg-red-50 dark:bg-red-900/20 rounded-full">
                                            <x-lucide-alert-circle class="w-3 h-3 text-red-600 dark:text-red-400" />
                                            <span class="text-xs font-medium text-red-700 dark:text-red-400">Error</span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </form>

    @script
    <script>
        ( function ()
        {
            const init = () =>
            {
                Alpine.data( 'sectionEditor', () => ( {
                    data: {
                        title: $wire.entangle( 'title' ).live,
                        subtitle: $wire.entangle( 'subtitle' ).live,
                        buttons: $wire.entangle( 'buttons' ).live,
                        backgroundType: $wire.entangle( 'backgroundType' ).live,
                        backgroundImages: $wire.entangle( 'backgroundImages' ).live,
                        custom: $wire.entangle( 'customFields' ).live,
                    },
                    states: {
                        title: 'saved',
                        subtitle: 'saved',
                        buttons: 'saved',
                        background: 'saved',
                        custom: 'saved'
                    },


                    init ()
                    {
                        // Listen for Livewire updates confirming save
                        window.addEventListener( 'field-saved', event =>
                        {
                            const { field, status } = event.detail;
                            this.states[ field ] = status;

                            if ( status === 'saved' ) {
                                setTimeout( () =>
                                {
                                    if ( this.states[ field ] === 'saved' ) {
                                        this.states[ field ] = 'idle';
                                    }
                                }, 2000 );
                            }
                        } );
                    },

                    save ( field )
                    {
                        this.states[ field ] = 'saving';
                        this.$wire.save( field, this.data[ field ] );
                    },

                    addButton ()
                    {
                        if ( !this.data.buttons ) this.data.buttons = [];
                        this.data.buttons.push( { label: '', url: '', icon: '', show: true } );
                        this.save( 'buttons' );
                    },

                    removeButton ( index )
                    {
                        this.data.buttons.splice( index, 1 );
                        this.save( 'buttons' );
                    },



                    confirmDelete ( index )
                    {
                        if ( confirm( 'Are you sure you want to delete this image?' ) ) {
                            this.$wire.removeBackgroundImage( index );
                        }
                    }
                } ) )
            };

            if ( window.Alpine ) {
                init();
            } else {
                document.addEventListener( 'alpine:init', init );
            }
        } )();
    </script>
    @endscript
</div>
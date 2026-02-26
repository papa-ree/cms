<div x-data="{
    step: 1,
    selectedUsage: @entangle('usage'),
    schemas: @js($this->schemas),
    sectionName: @entangle('name'),
    sectionSlug: @entangle('slug'),
    actived: @entangle('actived'),
    
    selectSchema(key) {
        this.selectedUsage = key;
        this.step = 2;
    },
    
    getSchemaIcon(iconName) {
        const iconMap = {
            'dock': 'dock',
            'layout-list': 'layout-list',
            'link': 'link',
            'layout-grid': 'layout-grid',
            'bar-chart-3': 'bar-chart-3',
            'megaphone': 'megaphone',
        };
        return iconMap[iconName] || 'box';
    }
}" x-cloak>
    <x-core::breadcrumb :items="[['label' => __('Sections'), 'route' => 'bale.cms.sections.index']]" :active="__('Create New Section')" />

    <x-core::page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8 select-none">

            {{-- STEP 1: SCHEMA SELECTION --}}
            <div x-show="step === 1" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">

                <div class="max-w-6xl mx-auto">
                    {{-- Header --}}
                    <div class="text-center mb-12">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 bg-linear-to-br from-purple-600 to-indigo-600 rounded-2xl mb-4">
                            <x-lucide-layout class="w-8 h-8 text-white" />
                        </div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-3">
                            {{ __('Choose Section Type') }}
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            {{ __('Select the type of section you want to create') }}
                        </p>
                    </div>

                    {{-- Schema Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <template x-for="(schema, key) in schemas" :key="key">
                            <button type="button" @click="selectSchema(key)"
                                class="group relative bg-white dark:bg-slate-800 cursor-pointer rounded-2xl p-6 border-2 border-gray-200 dark:border-slate-700 hover:border-purple-500 dark:hover:border-purple-500 transition-all duration-200 text-left hover:shadow-xl hover:-translate-y-1">

                                {{-- Icon --}}
                                <div
                                    class="inline-flex items-center justify-center w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-xl mb-4 group-hover:bg-purple-200 dark:group-hover:bg-purple-900/50 transition-colors">
                                    <template x-if="schema.icon === 'dock'">
                                        <x-lucide-dock class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                    <template x-if="schema.icon === 'layout-list'">
                                        <x-lucide-layout-list class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                    <template x-if="schema.icon === 'link'">
                                        <x-lucide-link class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                    <template x-if="schema.icon === 'layout-grid'">
                                        <x-lucide-layout-grid class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                    <template x-if="schema.icon === 'bar-chart-3'">
                                        <x-lucide-bar-chart-3 class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                    <template x-if="schema.icon === 'megaphone'">
                                        <x-lucide-megaphone class="w-7 h-7 text-purple-600 dark:text-purple-400" />
                                    </template>
                                </div>

                                {{-- Content --}}
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white"
                                        x-text="schema.label"></h3>
                                    <template x-if="schema.type">
                                        <span
                                            class="px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 rounded-md"
                                            x-text="schema.type"></span>
                                    </template>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2"
                                    x-text="schema.description"></p>

                                {{-- Hover Arrow --}}
                                <div
                                    class="absolute top-6 right-6 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <x-lucide-arrow-right class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
            </div>

            {{-- STEP 2: SECTION DETAILS --}}
            <div x-show="step === 2" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100">

                <div class="max-w-2xl mx-auto">
                    {{-- Header with Selected Schema --}}
                    <div class="text-center mb-8">
                        <button type="button" @click="step = 1"
                            class="inline-flex items-center gap-2 text-sm cursor-pointer text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 mb-4 transition-colors">
                            <x-lucide-arrow-left class="w-4 h-4" />
                            {{ __('Change section type') }}
                        </button>

                        <div
                            class="inline-flex items-center gap-3 px-4 py-2 bg-purple-50 dark:bg-purple-900/20 rounded-xl mb-4">
                            <div
                                class="w-8 h-8 bg-purple-100 dark:bg-purple-900/40 rounded-lg flex items-center justify-center">
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'dock'">
                                    <x-lucide-dock class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'layout-list'">
                                    <x-lucide-layout-list class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'link'">
                                    <x-lucide-link class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'layout-grid'">
                                    <x-lucide-layout-grid class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'bar-chart-3'">
                                    <x-lucide-bar-chart-3 class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'megaphone'">
                                    <x-lucide-megaphone class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'layout'">
                                    <x-lucide-layout class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'newspaper'">
                                    <x-lucide-newspaper class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'panel-bottom'">
                                    <x-lucide-panel-bottom class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                                <template x-if="selectedUsage && schemas[selectedUsage]?.icon === 'sparkles'">
                                    <x-lucide-sparkles class="w-5 h-5 text-purple-600 dark:text-purple-400" />
                                </template>
                            </div>
                            <span class="text-sm font-medium text-purple-900 dark:text-purple-300"
                                x-text="selectedUsage && schemas[selectedUsage]?.label"></span>
                        </div>

                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ __('Section Details') }}
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ __('Configure your new section') }}
                        </p>
                    </div>

                    {{-- Form --}}
                    <form
                        @submit.prevent="$wire.call('store', { ...Object.fromEntries(new FormData($event.target)), actived: actived })"
                        class="space-y-6" x-data="{ sectionName: '', sectionSlug: '', actived: true }">

                        {{-- Hidden Usage Field --}}
                        <input type="hidden" name="usage" x-model="selectedUsage">

                        {{-- Section Name --}}
                        <div>
                            <x-core::input :label="__('Section Name')" x-model="sectionName" name="name"
                                :placeholder="__('e.g. Main Hero, Latest News, Company Features')" autofocus />
                            <x-core::input-error for="name" />
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Display name for this section') }}</p>
                        </div>

                        {{-- URL Slug --}}
                        <div>
                            <x-core::input :label="__('URL Slug')" name="slug" x-slug="sectionName"
                                x-model="sectionSlug" placeholder="main-hero" />
                            <x-core::input-error for="slug" />
                            <p class="mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('Auto-generated from section name') }}
                            </p>
                        </div>

                        {{-- Active Toggle --}}
                        <div>
                            <div
                                class="flex items-center justify-between p-4 bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <x-lucide-toggle-left class="w-5 h-5 text-gray-600 dark:text-gray-400" />
                                    <div>
                                        <label for="section-active-toggle"
                                            class="text-sm font-medium text-gray-900 dark:text-white cursor-pointer">
                                            {{ __('Enable Section') }}
                                        </label>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ __('Show this section on your website') }}</p>
                                    </div>
                                </div>
                                <label for="section-active-toggle"
                                    class="relative inline-block w-12 h-6 cursor-pointer">
                                    <input type="checkbox" name="actived" id="section-active-toggle" x-model="actived"
                                        class="peer sr-only">
                                    <span
                                        class="absolute inset-0 bg-gray-300 rounded-full transition peer-checked:bg-emerald-600 dark:bg-gray-700 dark:peer-checked:bg-emerald-500"></span>
                                    <span
                                        class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition peer-checked:translate-x-6"></span>
                                </label>
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="flex justify-center pt-4">
                            <x-core::button :label="__('Create Section')" spinner type="submit">
                                <x-slot name="icon">
                                    <x-lucide-plus class="w-4 h-4" />
                                </x-slot>
                            </x-core::button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </x-core::page-container>
</div>
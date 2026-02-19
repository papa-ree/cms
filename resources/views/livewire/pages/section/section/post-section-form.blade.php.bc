<div>
    <form wire:submit='update' class="max-w-3xl">
        <div class="mb-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">Post Section Configuration</h3>

                {{-- Active Toggle --}}
                <div class="flex items-center gap-3">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model='section.is_active' class="sr-only peer" wire:change="toggle">
                        <div
                            class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
            </div>

            <div class="space-y-6">
                {{-- ID --}}
                <div>
                    <x-core::input label="Section ID" wire:model='section.id' />
                    <p class="text-xs text-gray-500 mt-1">Used for anchor links (e.g., #my-section)</p>
                    <x-core::input-error for="section.id" />
                </div>

                {{-- Title & Subtitle --}}
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700 space-y-4">
                    <div class="grid gap-4">
                        <x-core::input label="Title" wire:model='section.title' />
                        <div class="flex items-center">
                            <input type="checkbox" wire:model='section.show_title'
                                class="shrink-0 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                id="show-title">
                            <label for="show-title" class="ml-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                Show Title
                            </label>
                        </div>
                    </div>
                    <x-core::input-error for="section.title" />

                    <div class="grid gap-4 pt-4 border-t border-gray-200 dark:border-slate-600">
                        <x-core::input label="Subtitle" wire:model='section.subtitle' />
                        <div class="flex items-center">
                            <input type="checkbox" wire:model='section.show_subtitle'
                                class="shrink-0 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                id="show-subtitle">
                            <label for="show-subtitle"
                                class="ml-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                Show Subtitle
                            </label>
                        </div>
                    </div>
                    <x-core::input-error for="section.subtitle" />
                </div>

                {{-- Layout Settings --}}
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Layout Options</h4>

                    <div class="grid md:grid-cols-2 gap-6">
                        <div x-data="{grid_value:$wire.entangle('section.layouts.grid')}">
                            <x-core::input type="number" disabled label="Grid Columns" wire:model='section.layouts.grid'
                                x-model="grid_value" min="2" max="4" />
                            <div class="mt-2">
                                <x-core::input x-model="grid_value" min="2" max="4" step="1" useRangeSlide />
                            </div>
                            <x-core::input-error for="section.layouts.grid" />
                        </div>

                        <div x-data="{post_limit_value:$wire.entangle('section.layouts.post_limit')}">
                            <x-core::input disabled type="number" label="Post Limit"
                                wire:model='section.layouts.post_limit' x-model="post_limit_value" min="2" max="6" />
                            <div class="mt-2">
                                <x-core::input x-model="post_limit_value" min="2" max="6" step="1" useRangeSlide />
                            </div>
                            <x-core::input-error for="section.layouts.post_limit" />
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                    <h4 class="text-sm font-semibold text-gray-800 dark:text-white mb-4">Action Buttons</h4>
                    <div class="space-y-4">
                        @foreach ($section['buttons'] as $index => $button)
                            <div
                                class="p-3 bg-white dark:bg-slate-800 rounded-lg border border-gray-200 dark:border-slate-600">
                                <div class="grid gap-3">
                                    <div class="grid sm:grid-cols-2 gap-3">
                                        <x-core::input label="Label" wire:model='section.buttons.{{ $index }}.label' />
                                        <x-core::input label="Target URL" wire:model='section.buttons.{{ $index }}.url' />
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" wire:model='section.buttons.{{ $index }}.show'
                                            class="shrink-0 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                            id="show-button-{{$index}}">
                                        <label for="show-button-{{$index}}"
                                            class="ml-2 text-sm text-gray-500 dark:text-gray-400">
                                            Enable Button
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <x-core::input-error for="section.buttons" class="mt-2" />
                    <x-core::input-error for="section.buttons.*" class="mt-2" />
                </div>
            </div>

            <div class="mt-8 flex items-center justify-end gap-x-4 border-t border-gray-100 dark:border-slate-700 pt-6">
                <x-core::secondary-button link-reload :href="$url . '/#' . $section['id']" target="_blank" type="button"
                    label="Preview" />
                <x-core::button type="submit" label="Save Changes" />
            </div>
        </div>
    </form>
</div>
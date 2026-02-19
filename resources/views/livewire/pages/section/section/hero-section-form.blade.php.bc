<div>
    <form wire:submit='update' class="max-w-3xl">
        <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Hero Content</h3>
            <div class="space-y-6">
                <!-- Title -->
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
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
                    <x-core::input-error for="section.title" class="mt-2" />
                </div>

                <!-- Subtitle -->
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="grid gap-4">
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
                    <x-core::input-error for="section.subtitle" class="mt-2" />
                </div>

                <!-- Organization -->
                <div
                    class="bg-gray-50 dark:bg-slate-700/50 p-4 rounded-xl border border-gray-100 dark:border-slate-700">
                    <div class="grid gap-4">
                        <x-core::input label="Organization" wire:model='section.organization' />
                        <div class="flex items-center">
                            <input type="checkbox" wire:model='section.show_organization'
                                class="shrink-0 w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                id="show-organization">
                            <label for="show-organization"
                                class="ml-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
                                Show Organization
                            </label>
                        </div>
                    </div>
                    <x-core::input-error for="section.organization" class="mt-2" />
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
                                    <x-core::input label="Label"
                                        wire:model='section.buttons.{{ $index }}.label' />
                                    <x-core::input label="URL"
                                        wire:model='section.buttons.{{ $index }}.url' />
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
                <x-core::secondary-button link-reload :href="$url" target="_blank" type="button" label="Preview" />
                <x-core::button type="submit" label="Save Changes" />
            </div>
        </div>
    </form>

    <div class="mt-8 border-t border-gray-100 dark:border-slate-700 pt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Images</h3>
        <livewire:cms.pages.section.section.upload-image :slug="$slug" />
    </div>
</div>
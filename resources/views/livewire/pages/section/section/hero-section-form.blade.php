<div>
    <x-core::page-container>
        <form wire:submit='update'>
            <div class="mb-4 sm:mb-6">
                <div class="">
                    <x-core::input label="title" wire:model='section.title' />
                </div>
                <div class="flex">
                    <input type="checkbox" wire:model='section.show_title'
                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                        id="show-title">
                    <label for="show-title" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                        Show Title
                    </label>
                </div>
                <x-core::input-error for="section.title" />
            </div>

            <div class="mb-4 sm:mb-6">
                <div class="">
                    <x-core::input label="subtitle" wire:model='section.subtitle' />
                </div>
                <div class="flex">
                    <input type="checkbox" wire:model='section.show_title'
                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                        id="show-subtitle">
                    <label for="show-subtitle" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                        Show Subtitle
                    </label>
                </div>
                <x-core::input-error for="section.subtitle" />
            </div>

            <div class="mb-4 sm:mb-6">
                <div class="">
                    <x-core::input label="organization" wire:model='section.organization' />
                </div>
                <div class="flex">
                    <input type="checkbox" wire:model='section.show_organization'
                        class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                        id="show-organization">
                    <label for="show-organization" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                        Show organization
                    </label>
                </div>
                <x-core::input-error for="section.organization" />
            </div>

            @foreach ($section['buttons'] as $index => $button)
                <div class="mb-4 sm:mb-6">
                    <div class="">
                        <x-core::input label="{{$button['label']}}" wire:model='section.buttons.{{ $index }}.url' />
                    </div>
                    <div class="flex">
                        <input type="checkbox" wire:model='section.buttons.{{ $index }}.show'
                            class="shrink-0 mt-0.5 border-gray-200 rounded-sm text-emerald-600 focus:ring-emerald-500 checked:border-emerald-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-neutral-800 dark:border-neutral-700 dark:checked:bg-emerald-500 dark:checked:border-emerald-500 dark:focus:ring-offset-gray-800"
                            id="show-button-{{$index}}">
                        <label for="show-button-{{$index}}" class="text-sm text-gray-500 ms-3 dark:text-neutral-400">
                            Show Button
                        </label>
                    </div>
                </div>
            @endforeach
            <x-core::input-error for="section.buttons" />
            <x-core::input-error for="section.buttons.*" />

            <div class="flex items-center justify-end gap-x-4">
                <x-core::secondary-button link-reload :href="$url" target="_blank" type="button" label="preview" />
                <x-core::button type="submit" label="Save" />
            </div>
        </form>
    </x-core::page-container>

    <div class="mt-6">
        <livewire:cms.pages.section.section.upload-image :slug="$slug" />
    </div>
</div>
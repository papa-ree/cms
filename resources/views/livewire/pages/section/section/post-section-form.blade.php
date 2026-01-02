<div>
    <div class="grid grid-cols-2">
        <x-core::page-container>
            {{-- active toggle --}}
            <div class="hs-tooltip flex items-center justify-end gap-x-3">
                <label for="bale-tooltip-show-post-section"
                    class="hs-tooltip-toggle relative inline-block w-11 h-6 cursor-pointer">
                    <input type="checkbox" id="bale-tooltip-show-post-section" wire:model='section.is_active'
                        class="peer sr-only" wire:change="toggle">
                    <span
                        class="absolute inset-0 bg-gray-200 rounded-full transition-colors duration-200 ease-in-out peer-checked:bg-emerald-600 dark:bg-neutral-700 dark:peer-checked:bg-emerald-500 peer-disabled:opacity-50 peer-disabled:pointer-events-none"></span>
                    <span
                        class="absolute top-1/2 start-0.5 -translate-y-1/2 size-5 bg-white rounded-full shadow-xs transition-transform duration-200 ease-in-out peer-checked:translate-x-full dark:bg-neutral-400 dark:peer-checked:bg-white"></span>
                </label>
                <label for="bale-tooltip-show-post-section" class="text-sm text-gray-500 dark:text-neutral-400">
                    Allow Section
                </label>
                <div class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded-md shadow-2xs dark:bg-neutral-700"
                    role="tooltip">
                    Enable Post Section
                </div>
            </div>

            <form wire:submit='update'>
                <div class="mb-4 sm:mb-6">
                    <div class="">
                        <x-core::input label="id" wire:model='section.id' />
                    </div>
                    <x-core::input-error for="section.id" />
                </div>
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
                    <div class="" x-data="{grid_value:$wire.entangle('section.layouts.grid')}">
                        <x-core::input type="number" disabled label="Grid" wire:model='section.layouts.grid'
                            x-model="grid_value" min="2" max="4" />
                        <x-core::input x-model="grid_value" min="2" max="4" step="1" useRangeSlide />
                    </div>
                    <x-core::input-error for="section.layouts.grid" />
                </div>
                <div class="mb-4 sm:mb-6">
                    <div class="" x-data="{post_limit_value:$wire.entangle('section.layouts.post_limit')}">
                        <x-core::input disabled type="number" label="Post Limit" wire:model='section.layouts.post_limit'
                            x-model="post_limit_value" min="2" max="6" />
                        <x-core::input x-model="post_limit_value" min="2" max="6" step="1" useRangeSlide />
                    </div>
                    <x-core::input-error for="section.layouts.post_limit" />
                </div>
                <span>button</span>
                @foreach ($section['buttons'] as $index => $button)
                    <div class="mb-4 sm:mb-6">
                        <div class="mb-4">
                            <x-core::input label="label" wire:model='section.buttons.{{ $index }}.label' />
                        </div>
                        <div class="">
                            <x-core::input label="target" wire:model='section.buttons.{{ $index }}.url' />
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
                    <x-core::secondary-button link-reload :href="$url . '/#' . $section['id']" target="_blank"
                        type="button" label="preview" />
                    <x-core::button type="submit" label="Save" />
                </div>
            </form>
        </x-core::page-container>

        {{-- <x-core::page-container>
            <iframe src="http://localhost" frameborder="0" style="overflow:hidden;height:100%;width:100%" height="100%"
                width="100%" title="iframe"></iframe>
        </x-core::page-container> --}}
    </div>
</div>
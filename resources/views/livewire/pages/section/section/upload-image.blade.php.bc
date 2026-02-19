<div>
    <x-core::page-container>
        <form wire:submit='uploadImages'>
            <x-core::label value="background" />
            <div class="mb-4 sm:mb-6 gap-4 grid grid-cols-2 sm:grid-cols-4 xl:sm:grid-cols-6">
                @foreach ($section['backgrounds'] as $index => $background)
                    <div
                        class="relative flex items-center justify-center w-40 h-40 overflow-hidden transition-all duration-500 ease-in-out transform rounded-lg shadow-md cursor-pointer group hover:shadow-slate-500">
                        <img loading="lazy"
                            class="object-cover object-center w-40 h-40 max-w-full transition-all duration-500 ease-in-out transform bg-center bg-cover rounded-lg group-hover:scale-125"
                            src="{{ \Bale\Core\Support\Cdn::url('landing-page/' . $background['path']) }}"
                            alt="{{ $background['alt'] }}" loading="lazy">

                        @if (count($section['backgrounds']) > 1)
                            <div wire:click="deleteImage('{{ $background['path'] }}')"
                                class="absolute z-20 hidden p-1 transition-all duration-500 ease-in-out rounded-full group-hover:block bg-slate-500/50 hover:bg-white/80 hover:text-red-400 top-1 right-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <x-cms::filepond wire:model="backgrounds" allowImagePreview imagePreviewMaxHeight="200"
                allowFileTypeValidation acceptedFileTypes="['image/png', 'image/jpg', 'image/jpeg']"
                allowFileSizeValidation maxFileSize="512kb" multiple />

            <div class="flex items-center justify-end">
                <x-core::button type="submit" label="Save" />
            </div>
        </form>
    </x-core::page-container>
</div>
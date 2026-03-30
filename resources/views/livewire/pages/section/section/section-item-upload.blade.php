<div>
    {{--
    |--------------------------------------------------------------------------
    | Component: section-item-upload
    |--------------------------------------------------------------------------
    | Standalone upload section that appears AFTER an item has been saved.
    | Uploads are stored in content.items[n].uploads (not in the field key).
    --}}

    <div
        class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden"
        x-data="sectionItemUpload(@js($fileKeys))"
        @upload-saved.window="onUploadSaved($event.detail[0] ?? $event.detail)"
    >
        {{-- ── Section Header ── --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 dark:border-gray-700">
            <div class="flex items-center gap-2.5">
                <div class="p-2 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                    <x-lucide-paperclip class="w-4 h-4 text-violet-600" />
                </div>
                <div>
                    <h3 class="font-bold text-sm text-gray-900 dark:text-white">Upload Files</h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400">File tersimpan otomatis saat diupload</p>
                </div>
            </div>

            {{-- Auto-save status badge --}}
            <div>
                @if ($saveStatus === 'saving')
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs font-medium animate-pulse">
                        <x-lucide-loader-circle class="w-3 h-3 animate-spin" />
                        Menyimpan...
                    </span>
                @elseif ($saveStatus === 'saved')
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs font-medium">
                        <x-lucide-check-circle class="w-3 h-3" />
                        Tersimpan
                    </span>
                @else
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-400 text-xs font-medium">
                        <x-lucide-cloud class="w-3 h-3" />
                        Auto-save
                    </span>
                @endif
            </div>
        </div>

        {{-- ── Upload Fields ── --}}
        <div class="p-6 space-y-8">
            @foreach ($fileKeys as $key)
                <div wire:key="upload-field-{{ $key }}">
                    {{-- Field Label --}}
                    <div class="flex items-center gap-1.5 mb-3">
                        <x-lucide-paperclip class="w-4 h-4 text-violet-600" />
                        <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</label>
                        <span
                            class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 rounded">
                            file upload
                        </span>
                    </div>

                    {{-- Saved Uploads Gallery --}}
                    @php $keyUploads = $uploads[$key] ?? []; @endphp

                    @if (count($keyUploads) > 0)
                        <div class="flex flex-wrap gap-3 mb-4">
                            @foreach ($keyUploads as $fi => $upload)
                                <div
                                    class="relative group w-20 h-20 rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 shadow-sm shrink-0"
                                    wire:key="upload-thumb-{{ $key }}-{{ $fi }}">

                                    @if ($upload['file_type'] === 'image' || in_array(strtolower(pathinfo($upload['original_name'], PATHINFO_EXTENSION)), ['jpg','jpeg','png','gif','svg','webp']))
                                        {{-- Image thumbnail --}}
                                        <img src="{{ $upload['url'] }}" alt="{{ $upload['original_name'] }}"
                                            class="w-full h-full object-cover" />
                                    @else
                                        {{-- Document icon --}}
                                        <div
                                            class="w-full h-full flex flex-col items-center justify-center gap-1 px-1">
                                            @if ($upload['file_type'] === 'pdf')
                                                <span class="text-2xl">📄</span>
                                            @elseif ($upload['file_type'] === 'spreadsheet')
                                                <span class="text-2xl">📊</span>
                                            @elseif ($upload['file_type'] === 'document')
                                                <span class="text-2xl">📝</span>
                                            @elseif ($upload['file_type'] === 'video')
                                                <span class="text-2xl">🎬</span>
                                            @elseif ($upload['file_type'] === 'audio')
                                                <span class="text-2xl">🎵</span>
                                            @else
                                                <span class="text-2xl">📎</span>
                                            @endif
                                            <span class="text-[9px] font-bold uppercase text-gray-400 truncate w-full text-center">
                                                {{ pathinfo($upload['original_name'], PATHINFO_EXTENSION) }}
                                            </span>
                                        </div>
                                    @endif

                                    {{-- Hover overlay: link & delete --}}
                                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col">
                                        {{-- Clickable area (top half) --}}
                                        <a href="{{ $upload['url'] }}" target="_blank" 
                                           class="flex-1 flex flex-col items-center justify-center p-1 hover:bg-white/10 transition-colors"
                                           title="Buka {{ $upload['original_name'] }}">
                                            <x-lucide-external-link class="w-3.5 h-3.5 text-white mb-0.5" />
                                            <p class="text-[9px] text-white text-center leading-tight truncate w-full">
                                                {{ Str::limit($upload['original_name'], 12) }}
                                            </p>
                                        </a>
                                        {{-- Delete action (bottom) --}}
                                        <div class="p-1 flex justify-center border-t border-white/10">
                                            <button type="button"
                                                wire:click.stop="deleteUpload('{{ $key }}', {{ $fi }})"
                                                wire:confirm="Hapus file ini? Tindakan tidak dapat dibatalkan."
                                                class="flex items-center gap-1 justify-center px-1.5 py-0.5 bg-red-500 hover:bg-red-600 text-white text-[9px] font-semibold rounded w-full transition-colors">
                                                <x-lucide-trash-2 class="w-2.5 h-2.5" />
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Alpine: newly uploaded files (not yet refreshed by Livewire) --}}
                    <div x-show="pendingUploads['{{ $key }}'] && pendingUploads['{{ $key }}'].length > 0" class="flex flex-wrap gap-3 mb-4">
                        <template x-for="(file, fi) in pendingUploads['{{ $key }}']" :key="fi">
                            <div class="relative group w-20 h-20 rounded-xl overflow-hidden border border-violet-200 dark:border-violet-700 bg-gray-50 dark:bg-gray-900 shadow-sm shrink-0">
                                {{-- Image preview --}}
                                <template x-if="file.file_type === 'image'">
                                    <img :src="file.url" :alt="file.original_name" class="w-full h-full object-cover" />
                                </template>
                                {{-- Non-image preview --}}
                                <template x-if="file.file_type !== 'image'">
                                    <div class="w-full h-full flex flex-col items-center justify-center gap-1 px-1">
                                        <span class="text-2xl" x-text="file.file_type === 'pdf' ? '📄' : (file.file_type === 'spreadsheet' ? '📊' : (file.file_type === 'document' ? '📝' : '📎'))"></span>
                                        <span class="text-[9px] font-bold uppercase text-violet-400" x-text="(file.original_name || '').split('.').pop()"></span>
                                    </div>
                                </template>

                                {{-- Upload success badge --}}
                                <div class="absolute top-1 right-1">
                                    <span class="w-4 h-4 bg-emerald-500 rounded-full flex items-center justify-center">
                                        <x-lucide-check class="w-2.5 h-2.5 text-white" />
                                    </span>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- Upload Zone --}}
                    <x-core::upload-zone
                        wire:model.live="tempUpload.{{ $key }}"
                        accept="image/*,application/pdf,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.openxmlformats-officedocument.wordprocessingml.document,video/*,audio/*"
                        maxSize="10240"
                        multiple
                        label="{{ __('Drop files here or click to browse') }}"
                        hint="{{ __('Images, PDF, Excel, Word, Video, Audio — max 10MB') }}"
                    />
                    <x-core::input-error for="tempUpload.{{ $key }}" />
                </div>
            @endforeach
        </div>
    </div>

    <script>
        (() => {
            const _sectionItemUploadFactory = (fileKeys) => ({
                fileKeys,

                // Tracks newly uploaded files per key for instant UI feedback
                // before Livewire re-renders the saved gallery
                pendingUploads: Object.fromEntries(fileKeys.map(k => [k, []])),

                onUploadSaved(detail) {
                    const { key, url, original_name, mime, size, file_type } = detail;
                    if (!this.pendingUploads[key]) this.pendingUploads[key] = [];
                    this.pendingUploads[key].push({ key, url, original_name, mime, size, file_type });
                },
            });

            if (window.Alpine) {
                Alpine.data('sectionItemUpload', _sectionItemUploadFactory);
            }
            document.addEventListener('alpine:init', () => {
                Alpine.data('sectionItemUpload', _sectionItemUploadFactory);
            });
        })();
    </script>
</div>

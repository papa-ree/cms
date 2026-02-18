<div x-data="{
    item: @js($currentItem),
    tempInputs: {},
    fileKeys: @js($fileKeys),
    uploadedFiles: @js(
        collect($fileKeys)->mapWithKeys(function ($key) use ($currentItem, $slug, $orgSlug) {
            $urls = $currentItem[$key] ?? [];
            if (!is_array($urls))
                $urls = [$urls];

            return [
                $key => array_map(function ($u) use ($slug, $orgSlug) {
                    $name = basename($u);
                    // Reconstruct exact S3 path for existing files to allow deletion
                    $s3Path = $orgSlug . '/landing-page/items/' . $slug . '/' . $name;
                    return [
                        'url' => $u,
                        'name' => $name,
                        'mime' => '',
                        's3Path' => $s3Path
                    ];
                }, $urls)
            ];
        })->all()
    ),

    init() {
        window.addEventListener('file-uploaded', e => {
            const { key, url, name, mime, s3Path } = e.detail[0] ?? e.detail;
            
            if (!this.uploadedFiles[key]) this.uploadedFiles[key] = [];
            this.uploadedFiles[key].push({ url, name, mime, s3Path });
            
            if (!this.item[key]) this.item[key] = [];
            this.item[key].push(url);
        });
    },

    isFileKey(key) {
        return this.fileKeys.includes(key);
    },

    removeUploadedFile(key, index) {
        if (!confirm('Remove this file? This cannot be undone.')) return;

        const file = this.uploadedFiles[key][index];
        
        // Optimistically remove from UI
        this.uploadedFiles[key].splice(index, 1);
        this.item[key].splice(index, 1);

        // Call backend to delete from S3
        if (file.s3Path) {
            this.$wire.deleteFile(key, file.url, file.s3Path);
        }
    },

    detectMime(name) {
        if (!name) return '';
        const ext = name.split('.').pop().toLowerCase();
        if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext)) return 'image/' + ext;
        if (ext === 'pdf') return 'application/pdf';
        if (['xlsx', 'xls', 'csv'].includes(ext)) return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        if (['docx', 'doc'].includes(ext)) return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        return '';
    },

    fileIcon(file) {
        let mime = file.mime || this.detectMime(file.name);
        
        if (!mime) return 'file';
        if (mime.startsWith('image/')) return null; // Should show thumbnail
        if (mime === 'application/pdf') return 'pdf';
        if (mime.includes('spreadsheet') || mime.includes('excel') || mime.includes('xlsx') || mime.includes('xls')) return 'xlsx';
        if (mime.includes('word') || mime.includes('document') || mime.includes('docx') || mime.includes('doc')) return 'docx';
        return 'file';
    },

    isImage(file) {
        let mime = file.mime || this.detectMime(file.name);
        return mime && mime.startsWith('image/');
    },

    addValue(key) {
        let val = this.tempInputs[key] || '';
        if (typeof val === 'string' && val.trim() === '') return;
        if (!this.item[key]) this.item[key] = [];
        this.item[key].push(val);
        this.tempInputs[key] = '';
    },
    removeValue(key, index) {
        if (confirm('Are you sure you want to remove this value?')) {
            this.item[key].splice(index, 1);
        }
    },
    updateValue(key, index, newValue) {
        this.item[key][index] = newValue;
    }
}">
    @php
        $breadcrumbs = [
            ['label' => 'Sections', 'route' => 'bale.cms.sections.index'],
            [
                'label' => $name,
                'route' => 'bale.cms.sections.view-searchable',
                'params' => $slug
            ]
        ];
    @endphp

    <x-core::breadcrumb :items="$breadcrumbs" :active="$editMode ? 'Edit Item' : 'Create Item'" />

    {{-- Header Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                            <x-lucide-file-plus class="w-8 h-8 text-white" />
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-white md:text-4xl">
                                {{ $editMode ? 'Edit Item' : 'Create New Item' }}
                            </h1>
                        </div>
                    </div>
                    <p class="text-white/90 text-lg mb-2">
                        {{ $editMode ? 'Update' : 'Add new data to' }}: <span class="font-semibold">{{ $name }}</span>
                    </p>
                    <p class="text-white/80 text-sm">
                        Fill in the fields below based on your defined keys
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 shrink-0 justify-center">
                    <a href="{{ route('bale.cms.sections.view-searchable', $slug) }}" wire:navigate
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/20 backdrop-blur-md hover:bg-white/30 border border-white/30 rounded-lg text-sm font-medium text-white transition-all">
                        <x-lucide-arrow-left class="w-4 h-4 hidden lg:block" />
                        Back to Table
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Help Guide --}}
    <div
        class="mb-8 p-5 bg-linear-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl">
        <div class="flex items-start gap-4">
            <div class="p-3 bg-blue-600 rounded-xl shadow-lg">
                <x-lucide-lightbulb class="w-6 h-6 text-white" />
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">How to Use</h3>
                <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                    Each field can have multiple values. Text fields: type and click Add. File fields: drag & drop or
                    click to upload.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Press Enter or click Add for text
                            values</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Drag & drop or click to upload
                            files</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Supports images, PDF, XLSX, DOCX &
                            more</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Max 10MB per file</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Item Form --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="p-2.5 bg-linear-to-br from-blue-500 to-blue-600 rounded-lg shadow-md">
                <x-lucide-edit class="w-5 h-5 text-white" />
            </div>
            <div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-white">Item Data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $editMode ? 'Modify existing values' : 'Enter values for each field' }}
                </p>
            </div>
        </div>

        <div class="space-y-5">
            @foreach ($availableKeys as $key)
                @continue(in_array($key, ['id', 'created_at', 'updated_at']))
                <div class="p-4 bg-gray-50 dark:bg-gray-900/50 rounded-xl border border-gray-200 dark:border-gray-700">

                    {{-- Key Label --}}
                    <div class="flex items-center gap-1.5 mb-3">
                        @if (in_array($key, $fileKeys))
                            <x-lucide-paperclip class="w-4 h-4 text-violet-600" />
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</label>
                            <span
                                class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-violet-100 text-violet-700 dark:bg-violet-900/30 dark:text-violet-400 rounded">file
                                upload</span>
                        @else
                            <x-lucide-tag class="w-4 h-4 text-blue-600" />
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</label>
                        @endif
                    </div>

                    @if (in_array($key, $fileKeys))
                        {{-- ── FILE UPLOAD FIELD (FilePond) ── --}}

                        {{-- FilePond Dropzone --}}
                        <div wire:ignore x-data x-init="() => {
                                            $wire.set('activeUploadKey', '{{ $key }}');
                                            const pond = FilePond.create($refs['filepond_{{ $key }}']);
                                            pond.setOptions({
                                                allowMultiple: true,
                                                labelIdle: 'Drag & drop files or <span class=\'filepond--label-action\'>Browse</span>',
                                                server: {
                                                    process: (fieldName, file, metadata, load, error, progress, abort) => {
                                                        $wire.set('activeUploadKey', '{{ $key }}');
                                                        @this.upload('tempUpload', file, load, error, progress);
                                                    },
                                                    revert: (filename, load) => {
                                                        @this.removeUpload('tempUpload', filename, load);
                                                    },
                                                },
                                                allowImagePreview: true,
                                                imagePreviewMaxHeight: 200,
                                                allowFileTypeValidation: false,
                                                allowFileSizeValidation: true,
                                                maxFileSize: '10MB',
                                            });
                                        }">
                            <input type="file" hidden x-ref="filepond_{{ $key }}" />
                        </div>

                        {{-- Uploaded Files Grid --}}
                        <div class="mt-3" x-show="uploadedFiles['{{ $key }}'] && uploadedFiles['{{ $key }}'].length > 0">
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-check-circle class="w-3.5 h-3.5 text-emerald-600" />
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    Uploaded (<span
                                        x-text="uploadedFiles['{{ $key }}'] ? uploadedFiles['{{ $key }}'].length : 0"></span>)
                                </span>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                                <template x-for="(file, fIndex) in uploadedFiles['{{ $key }}']" :key="fIndex">
                                    <div
                                        class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all">

                                        {{-- Image preview --}}
                                        <template x-if="isImage(file)">
                                            <img :src="file.url" :alt="file.name" class="w-full h-24 object-cover" />
                                        </template>

                                        {{-- Non-image file icon --}}
                                        <template x-if="!isImage(file)">
                                            <div class="w-full h-24 flex flex-col items-center justify-center gap-1" :class="{
                                                                    'bg-red-50 dark:bg-red-900/20': fileIcon(file) === 'pdf',
                                                                    'bg-green-50 dark:bg-green-900/20': fileIcon(file) === 'xlsx',
                                                                    'bg-blue-50 dark:bg-blue-900/20': fileIcon(file) === 'docx',
                                                                    'bg-gray-50 dark:bg-gray-900/20': fileIcon(file) === 'file'
                                                                }">
                                                <span class="text-3xl"
                                                    x-text="fileIcon(file) === 'pdf' ? '📄' : (fileIcon(file) === 'xlsx' ? '📊' : (fileIcon(file) === 'docx' ? '📝' : '📎'))"></span>
                                                <span class="text-xs font-bold uppercase text-gray-500"
                                                    x-text="file.name ? file.name.split('.').pop() : 'file'"></span>
                                            </div>
                                        </template>

                                        {{-- File name + remove --}}
                                        <div class="p-2">
                                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate" :title="file.name"
                                                x-text="file.name"></p>
                                        </div>

                                        {{-- Remove button (visible on hover) --}}
                                        <button type="button" @click="removeUploadedFile('{{ $key }}', fIndex)"
                                            class="absolute top-1 right-1 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity shadow-md"
                                            title="Remove file">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Empty state --}}
                        <div x-show="!uploadedFiles['{{ $key }}'] || uploadedFiles['{{ $key }}'].length === 0"
                            class="mt-2 p-3 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">No files uploaded yet.</p>
                        </div>

                    @else
                        {{-- ── TEXT INPUT FIELD (existing behavior) ── --}}

                        {{-- Input + Add Button --}}
                        <div class="mb-3">
                            <div class="flex gap-2">
                                <div class="flex-1">
                                    @if ($key == 'date')
                                        <x-core::input type="date" x-model="tempInputs['{{ $key }}']"
                                            @keydown.enter="addValue('{{ $key }}')" placeholder="Select date" />
                                    @else
                                        <x-core::input x-model="tempInputs['{{ $key }}']" @keydown.enter="addValue('{{ $key }}')"
                                            placeholder="Enter {{ $key }}" />
                                    @endif
                                </div>
                                <button type="button" @click="addValue('{{ $key }}')"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all">
                                    <x-lucide-plus class="w-4 h-4" />
                                    Add
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Press Enter or click Add to add value
                            </p>
                        </div>

                        {{-- Display Values as Tags --}}
                        <div x-show="item['{{ $key }}'] && item['{{ $key }}'].length > 0">
                            <div class="flex items-center gap-2 mb-2">
                                <x-lucide-list class="w-3.5 h-3.5 text-gray-600 dark:text-gray-400" />
                                <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    Values (<span x-text="item['{{ $key }}'] ? item['{{ $key }}'].length : 0"></span>)
                                </span>
                            </div>
                            <div
                                class="flex flex-wrap gap-2 p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 max-h-48 overflow-y-auto">
                                <template x-for="(value, vIndex) in item['{{ $key }}']" :key="vIndex">
                                    <div class="hs-dropdown relative inline-flex [--auto-close:inside]">
                                        <button :id="'dropdown-{{ $key }}-' + vIndex" type="button"
                                            class="hs-dropdown-toggle inline-flex items-center gap-x-2 px-3 py-1.5 text-sm font-medium rounded-lg border border-blue-200 bg-blue-50 text-gray-800 shadow-sm hover:bg-blue-100 disabled:opacity-50 disabled:pointer-events-none dark:bg-blue-900/30 dark:border-blue-800 dark:text-white dark:hover:bg-blue-900/50">
                                            <span x-text="value"></span>
                                            <svg class="hs-dropdown-open:rotate-180 size-4" xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="m6 9 6 6 6-6" />
                                            </svg>
                                        </button>

                                        <div class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[300px] z-50 bg-white shadow-xl rounded-xl p-4 border border-gray-100 dark:bg-gray-800 dark:border-gray-700"
                                            :aria-labelledby="'dropdown-{{ $key }}-' + vIndex">
                                            <div x-data="{ editedValue: value }" x-init="$watch('value', v => editedValue = v)">
                                                <label class="block text-sm font-bold mb-2 text-gray-700 dark:text-gray-200">
                                                    Edit Value
                                                </label>
                                                <textarea x-model="editedValue" @keydown.stop
                                                    class="py-3 px-4 block w-full bg-gray-50 border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                                                    rows="4" placeholder="Enter value..."></textarea>

                                                <div
                                                    class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <button type="button" @click="removeValue('{{ $key }}', vIndex)"
                                                        class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent text-red-600 hover:bg-red-50 focus:outline-none focus:bg-red-50 disabled:opacity-50 disabled:pointer-events-none dark:text-red-500 dark:hover:bg-red-900/20">
                                                        <x-lucide-trash-2 class="w-4 h-4" />
                                                        Remove
                                                    </button>
                                                    <button type="button"
                                                        @click="updateValue('{{ $key }}', vIndex, editedValue); $el.closest('.hs-dropdown').classList.remove('open');"
                                                        class="py-2 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                                                        <x-lucide-check class="w-4 h-4" />
                                                        Update
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div x-show="!item['{{ $key }}'] || item['{{ $key }}'].length === 0"
                            class="p-3 bg-white dark:bg-gray-800 border border-dashed border-gray-300 dark:border-gray-700 rounded-lg text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                No values yet. Add your first value above.
                            </p>
                        </div>
                    @endif

                </div>
            @endforeach
        </div>
    </div>

    {{-- Save Section --}}
    <div
        class="mt-6 flex items-center justify-between p-6 bg-gray-50 dark:bg-gray-900/50 rounded-2xl border border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-3">
            <x-lucide-info class="w-5 h-5 text-blue-600" />
            <div>
                <p class="text-sm font-medium text-gray-900 dark:text-white">Ready to save?</p>
                <p class="text-xs text-gray-600 dark:text-gray-400">
                    {{ $editMode ? 'This will update the existing item' : 'This will add a new item to the collection' }}
                </p>
            </div>
        </div>
        <button type="button" @click="$wire.save(item)"
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-md transition-all">
            <x-lucide-save class="w-4 h-4" />
            {{ $editMode ? 'Update Item' : 'Create Item' }}
        </button>
    </div>

    @script
    <script>
        FilePond.registerPlugin( FilePondPluginFileValidateType );
        FilePond.registerPlugin( FilePondPluginFileValidateSize );
        FilePond.registerPlugin( FilePondPluginImagePreview );
    </script>
    @endscript

    <script>
        document.addEventListener( 'livewire:navigated', () =>
        {
            window.HSStaticMethods.autoInit();
        } );
    </script>
</div>
<div x-data="baleCreateItem">

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
                    Setiap field dapat memiliki beberapa nilai. Field teks: ketik dan klik Tambah. Field file: drag
                    &amp; drop atau klik untuk upload. Field social media: masukkan URL profil platform.
                </p>
                <div class="grid gap-2 md:grid-cols-2">
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Tekan Enter atau klik Tambah untuk nilai
                            teks</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Drag &amp; drop atau klik untuk upload
                            file</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">Key bernama <code
                                class="text-xs bg-pink-100 text-pink-700 px-1 rounded">facebook</code>, <code
                                class="text-xs bg-pink-100 text-pink-700 px-1 rounded">instagram</code>, dll. otomatis
                            tampil sebagai Social Media input</span>
                    </div>
                    <div class="flex items-start gap-2">
                        <x-lucide-check class="w-4 h-4 text-blue-600 mt-0.5" />
                        <span class="text-sm text-gray-600 dark:text-gray-400">File: max 10MB. Format: gambar, PDF,
                            XLSX, DOCX</span>
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
                        @elseif (in_array($key, $socialKeys))
                            <x-lucide-share-2 class="w-4 h-4 text-pink-500" />
                            <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">{{ $key }}</label>
                            <span
                                class="ml-1 px-1.5 py-0.5 text-xs font-medium bg-pink-100 text-pink-700 dark:bg-pink-900/30 dark:text-pink-400 rounded">social
                                media</span>
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

                    @elseif (in_array($key, $socialKeys))
                        {{-- ── SOCIAL MEDIA INPUT FIELD ── --}}
                        <div x-data="{ platform: getSocialPlatform('{{ $key }}') }" class="space-y-3">

                            {{-- Platform Header Badge --}}
                            <div class="flex items-center gap-3 p-3 rounded-xl text-white"
                                :style="'background: linear-gradient(135deg,' + platform.color + 'dd,' + platform.color + '99)'">
                                <div class="w-8 h-8 flex-shrink-0" x-html="platform.icon"></div>
                                <div>
                                    <p class="font-semibold text-sm" x-text="platform.name"></p>
                                    <p class="text-xs text-white/80">Tambahkan link profil / halaman</p>
                                </div>
                                <div class="ml-auto">
                                    <span class="text-xs bg-white/20 px-2 py-0.5 rounded-full"
                                        x-text="(item['{{ $key }}'] || []).length + ' link'"></span>
                                </div>
                            </div>

                            {{-- URL Input + Add Button --}}
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    {{-- Link icon inside input --}}
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </div>
                                    <input type="url" x-model="tempInputs['{{ $key }}']" @keydown.enter="addValue('{{ $key }}')"
                                        :placeholder="platform.placeholder"
                                        class="block w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-400 focus:border-transparent placeholder-gray-400 dark:placeholder-gray-500 transition-all" />
                                </div>
                                <button type="button" @click="addValue('{{ $key }}')" :style="'background:' + platform.color"
                                    class="inline-flex items-center gap-1.5 px-4 py-2.5 text-white font-semibold rounded-xl shadow-md transition-all hover:opacity-90 whitespace-nowrap text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Tambah
                                </button>
                            </div>
                            <p class="text-xs text-gray-400">Tekan Enter atau klik Tambah untuk menambahkan link</p>

                            {{-- Saved Social Links --}}
                            <div x-show="item['{{ $key }}'] && item['{{ $key }}'].length > 0" class="space-y-2">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Link
                                    Tersimpan</p>
                                <template x-for="(url, idx) in item['{{ $key }}']" :key="idx">
                                    <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-900 rounded-xl border group transition-all hover:shadow-sm"
                                        :class="platform.border">
                                        {{-- Platform mini-icon --}}
                                        <div class="w-5 h-5 flex-shrink-0" :class="platform.text" x-html="platform.icon"></div>

                                        {{-- URL clickable --}}
                                        <a :href="url" target="_blank" rel="noopener"
                                            class="flex-1 text-sm truncate hover:underline" :class="platform.text"
                                            x-text="url"></a>

                                        {{-- Edit inline button --}}
                                        <div class="hs-dropdown relative inline-flex [--auto-close:inside]">
                                            <button :id="'soc-dd-{{ $key }}-' + idx" type="button"
                                                class="hs-dropdown-toggle p-1.5 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors opacity-0 group-hover:opacity-100">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <div class="hs-dropdown-menu transition-[opacity,margin] hs-dropdown-open:opacity-100 opacity-0 hidden min-w-[280px] z-50 bg-white dark:bg-gray-800 shadow-xl rounded-xl p-4 border border-gray-100 dark:border-gray-700"
                                                :aria-labelledby="'soc-dd-{{ $key }}-' + idx">
                                                <div x-data="{ editedUrl: url }" x-init="$watch('url', v => editedUrl = v)">
                                                    <label
                                                        class="block text-xs font-bold mb-1.5 text-gray-700 dark:text-gray-200">Edit
                                                        URL</label>
                                                    <input type="url" x-model="editedUrl" @keydown.stop
                                                        class="block w-full py-2 px-3 text-sm border border-gray-200 dark:border-gray-700 rounded-lg dark:bg-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-pink-400"
                                                        placeholder="https://..." />
                                                    <div
                                                        class="flex justify-between items-center mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                                        <button type="button" @click="removeValue('{{ $key }}', idx)"
                                                            class="inline-flex items-center gap-1 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 px-2 py-1 rounded-lg transition-colors">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                        <button type="button"
                                                            @click="updateValue('{{ $key }}', idx, editedUrl); $el.closest('.hs-dropdown').classList.remove('open');"
                                                            :style="'background:' + platform.color"
                                                            class="inline-flex items-center gap-1 text-sm text-white px-3 py-1.5 rounded-lg transition-opacity hover:opacity-90">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Quick delete --}}
                                        <button type="button" @click="removeValue('{{ $key }}', idx)"
                                            class="p-1.5 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            {{-- Empty state --}}
                            <div x-show="!item['{{ $key }}'] || item['{{ $key }}'].length === 0"
                                class="flex flex-col items-center justify-center py-6 border-2 border-dashed rounded-xl text-center"
                                :class="platform.border">
                                <div class="w-10 h-10 mb-2 opacity-30" :class="platform.text" x-html="platform.icon"></div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada link <span
                                        x-text="platform.name"></span></p>
                                <p class="text-xs text-gray-400 mt-0.5">Tambahkan URL di atas</p>
                            </div>

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

    {{-- Social platform map (kept in plain
    <script> so SVG quotes never break x-data)--}}
        <script>
            window.baleSocialPlatforms = {
                facebook:  { name: 'Facebook',  color: '#1877F2', text: 'text-blue-600',   border: 'border-blue-200',   placeholder: 'https://facebook.com/yourpage',        icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>' },
            instagram: { name: 'Instagram', color: '#E1306C', text: 'text-pink-600',   border: 'border-pink-200',   placeholder: 'https://instagram.com/yourprofile',   icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" /></svg>' },
            youtube:   { name: 'YouTube',   color: '#FF0000', text: 'text-red-600',    border: 'border-red-200',    placeholder: 'https://youtube.com/@yourchannel',    icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" /></svg>' },
            whatsapp:  { name: 'WhatsApp',  color: '#25D366', text: 'text-green-600', border: 'border-green-200',  placeholder: 'https://wa.me/628xxxxxxxx',           icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>' },
            tiktok:    { name: 'TikTok',    color: '#010101', text: 'text-gray-800',  border: 'border-gray-300',   placeholder: 'https://tiktok.com/@yourprofile',     icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z" /></svg>' },
            twitter:   { name: 'Twitter/X', color: '#1DA1F2', text: 'text-sky-600',   border: 'border-sky-200',    placeholder: 'https://twitter.com/yourhandle',      icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" /></svg>' },
            x:         { name: 'X',         color: '#000000', text: 'text-gray-900',  border: 'border-gray-300',   placeholder: 'https://x.com/yourhandle',            icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>' },
            linkedin:  { name: 'LinkedIn',  color: '#0A66C2', text: 'text-blue-700',  border: 'border-blue-200',   placeholder: 'https://linkedin.com/in/yourprofile',  icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" /></svg>' },
            telegram:  { name: 'Telegram',  color: '#26A5E4', text: 'text-cyan-600',  border: 'border-cyan-200',   placeholder: 'https://t.me/yourprofile',            icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" /></svg>' },
            pinterest: { name: 'Pinterest', color: '#BD081C', text: 'text-red-700',   border: 'border-red-200',    placeholder: 'https://pinterest.com/yourprofile',   icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738a.36.36 0 01.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.632-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z" /></svg>' },
            snapchat:  { name: 'Snapchat',  color: '#FFFC00', text: 'text-yellow-700',border: 'border-yellow-200', placeholder: 'https://snapchat.com/add/yourusername', icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.206.793c.99 0 4.347.276 5.93 3.821.529 1.193.403 3.219.317 4.814l-.01.143c-.118 2.143-.271 4.908 1.596 6.291.209.163.49.234.254.567-.373.48-1.267.774-2.139.774-.13 0-.261-.009-.389-.025-.82-.118-1.493-.393-2.17-.66-.59-.233-1.186-.468-1.849-.468-.639 0-1.235.232-1.826.463-.674.267-1.352.537-2.168.655-.13.017-.261.026-.392.026-.894 0-1.786-.297-2.154-.778-.238-.335.052-.403.252-.565 1.871-1.384 1.714-4.148 1.596-6.291l-.01-.143c-.086-1.595-.212-3.621.317-4.814C7.86 1.069 11.215.793 12.206.793zm0 21.617c-.007 0-.015 0-.022.001-2.012.07-3.978-.267-5.88-.999C5.095 19.614 4 19.053 4 18.23c0-.383.302-.627.619-.627.057 0 .113.005.167.016.316.073.636.152.967.206a5.89 5.89 0 001.067.098c1.284 0 2.484-.461 3.354-1.302.082-.075.168-.145.262-.205.279-.178.598-.272.934-.272.332 0 .649.093.926.269.097.062.186.134.27.211.874.84 2.079 1.299 3.368 1.299.326 0 .659-.034.988-.101.337-.054.659-.133.976-.207a.799.799 0 01.167-.016c.317 0 .619.244.619.627 0 .819-1.096 1.384-2.302 1.079-1.893.731-3.854 1.068-5.875.999l-.022-.001z" /></svg>' },
            threads:   { name: 'Threads',   color: '#101010', text: 'text-gray-900',  border: 'border-gray-300',   placeholder: 'https://threads.net/@yourprofile',    icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12.186 24h-.007c-3.581-.024-6.334-1.205-8.184-3.509C2.35 18.44 1.5 15.586 1.472 12.01v-.017c.03-3.579.879-6.43 2.525-8.482C5.845 1.205 8.6.024 12.18 0h.014c2.746.02 5.043.725 6.826 2.098 1.677 1.29 2.858 3.13 3.509 5.467l-2.04.569c-1.104-3.96-3.898-5.984-8.304-6.015-2.91.022-5.11.936-6.54 2.717C4.307 6.504 3.616 8.914 3.589 12c.027 3.086.718 5.496 2.057 7.164 1.43 1.783 3.631 2.698 6.54 2.717 1.868-.017 3.507-.467 4.626-1.362.96-.773 1.524-1.836 1.674-3.16H15.94c-.272 1.051-.858 1.907-1.748 2.542-1.024.728-2.363 1.099-4.003 1.099h-.003c-1.94 0-3.43-.484-4.44-1.441-.837-.797-1.3-1.892-1.367-3.237h11.543v-.002c.01-.174.019-.349.019-.526 0-2.512-.72-4.578-2.08-5.981-1.327-1.373-3.123-2.081-5.352-2.103a7.25 7.25 0 00-.161.002c-1.63.02-3.017.519-4.125 1.481-.96.832-1.57 1.984-1.815 3.424h2.088c.215-.837.64-1.527 1.263-2.048.74-.628 1.698-.956 2.84-.972h.02z" /></svg>' },
            line:      { name: 'LINE',      color: '#00C300', text: 'text-green-600', border: 'border-green-200',  placeholder: 'https://line.me/ti/p/@yourID',        icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M24 10.304c0-5.369-5.383-9.738-12-9.738-6.616 0-12 4.369-12 9.738 0 4.814 4.269 8.846 10.036 9.608.391.084.923.258 1.058.59.121.301.079.766.038 1.08l-.164 1.02c-.045.301-.27 1.186 1.048.645 1.316-.539 7.091-4.179 9.684-7.15C23.147 13.956 24 12.228 24 10.304z" /></svg>' },
            wechat:    { name: 'WeChat',    color: '#07C160', text: 'text-green-500', border: 'border-green-200',  placeholder: 'https://weixin.qq.com/',              icon: '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M8.691 2.188C3.891 2.188 0 5.476 0 9.53c0 2.212 1.17 4.203 3.002 5.55a.59.59 0 01.213.665l-.39 1.48c-.019.07-.048.141-.048.213 0 .163.13.295.29.295a.326.326 0 00.167-.054l1.903-1.114a.864.864 0 01.717-.098 10.16 10.16 0 002.837.403c.276 0 .543-.027.811-.05-.857-2.578.157-4.972 1.932-6.446 1.703-1.415 3.882-1.98 5.853-1.838-.576-3.583-4.196-6.348-8.596-6.348zM5.785 5.991c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178A1.17 1.17 0 014.623 7.17c0-.651.52-1.18 1.162-1.18zm5.813 0c.642 0 1.162.529 1.162 1.18a1.17 1.17 0 01-1.162 1.178 1.17 1.17 0 01-1.162-1.178c0-.651.52-1.18 1.162-1.18zm5.34 2.867c-1.797-.052-3.746.512-5.26 1.786-1.543 1.299-2.31 3.164-1.998 5.47 0 4.144 4.084 6.773 8.066 6.773.814 0 1.617-.113 2.388-.333a.722.722 0 01.598.082l1.584.926a.272.272 0 00.14.047c.134 0 .24-.11.24-.247 0-.06-.023-.12-.038-.177l-.327-1.233a.49.49 0 01.177-.554C23.072 20.048 24 18.37 24 16.475c0-3.808-3.254-5.751-7.062-5.617zm-3.307 2.67c.535 0 .969.441.969.983a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.983.969-.983zm6.4 0c.535 0 .969.441.969.983a.976.976 0 01-.969.983.976.976 0 01-.969-.983c0-.542.434-.983.969-.983z" /></svg>' },
        };

        Alpine.data('baleCreateItem', () => ({
                item:          @js($currentItem),
            tempInputs:    { },
            fileKeys:      @js($fileKeys),
            socialKeys:    @js($socialKeys),
            uploadedFiles: @js(
                collect($fileKeys)->mapWithKeys(function ($key) use ($currentItem, $slug, $orgSlug) {
                    $urls = $currentItem[$key] ?? [];
                    if (!is_array($urls))
                        $urls = [$urls];
                    return [
                        $key => array_map(function ($u) use ($slug, $orgSlug) {
                            $name = basename($u);
                            $s3Path = $orgSlug . '/landing-page/items/' . $slug . '/' . $name;
                            return ['url' => $u, 'name' => $name, 'mime' => '', 's3Path' => $s3Path];
                        }, $urls)
                    ];
                })->all()
            ),

            init() {
                window.addEventListener( 'file-uploaded', e =>
                {
                    const { key, url, name, mime, s3Path } = e.detail[ 0 ] ?? e.detail;
                    if ( !this.uploadedFiles[ key ] ) this.uploadedFiles[ key ] = [];
                    this.uploadedFiles[ key ].push( { url, name, mime, s3Path } );
                    if ( !this.item[ key ] ) this.item[ key ] = [];
                    this.item[ key ].push( url );
                } );
            },

            isFileKey(key)   { return this.fileKeys.includes(key); },
            isSocialKey(key) { return this.socialKeys.includes(key); },

            getSocialPlatform(key) {
                const p = window.baleSocialPlatforms;
            const k = key.toLowerCase();
            if (p[k]) return p[k];
            for (const [n, d] of Object.entries(p)) { if (k.endsWith('_' + n)) return d; }
            for (const [n, d] of Object.entries(p)) { if (k.startsWith(n + '_')) return d; }
            return { name: key, color: '#6366f1', text: 'text-indigo-600', border: 'border-indigo-200', placeholder: 'https://...', icon: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101" /><path d="M10.172 13.828a4 4 0 015.656 0l4 4a4 4 0 01-5.656 5.656l-1.102-1.101" /></svg>' };
            },

            removeUploadedFile(key, index) {
                if (!confirm('Remove this file? This cannot be undone.')) return;
            const file = this.uploadedFiles[key][index];
            this.uploadedFiles[key].splice(index, 1);
            this.item[key].splice(index, 1);
            if (file.s3Path) this.$wire.deleteFile(key, file.url, file.s3Path);
            },

            detectMime(name) {
                if (!name) return '';
            const ext = name.split('.').pop().toLowerCase();
            if (['jpg','jpeg','png','gif','webp','svg'].includes(ext)) return 'image/' + ext;
            if (ext === 'pdf') return 'application/pdf';
            if (['xlsx','xls','csv'].includes(ext)) return 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
            if (['docx','doc'].includes(ext)) return 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
            return '';
            },

            fileIcon(file) {
                const mime = file.mime || this.detectMime(file.name);
            if (!mime) return 'file';
            if (mime.startsWith('image/')) return null;
            if (mime === 'application/pdf') return 'pdf';
            if (mime.includes('spreadsheet') || mime.includes('excel') || mime.includes('xlsx') || mime.includes('xls')) return 'xlsx';
            if (mime.includes('word') || mime.includes('document') || mime.includes('docx') || mime.includes('doc')) return 'docx';
            return 'file';
            },

            isImage(file) {
                const mime = file.mime || this.detectMime(file.name);
            return mime && mime.startsWith('image/');
            },

            addValue(key) {
                const val = this.tempInputs[key] || '';
            if (typeof val === 'string' && val.trim() === '') return;
            if (!this.item[key]) this.item[key] = [];
            this.item[key].push(val);
            this.tempInputs[key] = '';
            },
            removeValue(key, index) {
                if (confirm('Are you sure you want to remove this value?')) this.item[key].splice(index, 1);
            },
            updateValue(key, index, newValue) {
                this.item[ key ][ index ] = newValue;
            },
        }));

        document.addEventListener( 'livewire:navigated', () =>
            {
                window.HSStaticMethods.autoInit();
        } );
    </script>
</div>
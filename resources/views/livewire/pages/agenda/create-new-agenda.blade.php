<div>
    <x-core::breadcrumb :items="[['label' => __('Agenda'), 'route' => 'bale.cms.agendas.index']]"
        active="{{ __('Buat Agenda Baru') }}" />

    <x-core::page-container>
        <div class="w-full px-4 py-6 mx-auto sm:px-6 lg:px-8 lg:py-8">
            <div class="max-w-4xl mx-auto space-y-8">

                <div class="text-center">
                    <h1 class="text-2xl font-bold text-gray-800 sm:text-3xl dark:text-white">
                        {{ __('Buat Agenda') }}
                    </h1>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">
                        {{ __('Jadwalkan kegiatan sekali jalan atau jadwal berulang dengan pembuat aturan pengulangan.') }}
                    </p>
                </div>

                <form @submit.prevent="$wire.call('store', Object.fromEntries(new FormData($event.target)))" x-cloak>
                    {{-- ─── Basic Information ─────────────────────────── --}}
                    <div class="mb-6 overflow-hidden bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-3 px-6 py-4 bg-purple-50/60 dark:bg-purple-900/10 border-b border-purple-100 dark:border-purple-900/30">
                            <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <x-lucide-info class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Informasi Dasar') }}</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('Judul, jadwal, dan konteks opsional') }}</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            <div>
                                <x-core::input label="{{ __('Judul') }}" name="title" wire:model="title" autofocus
                                    placeholder="{{ __('Contoh: Rapat mingguan tim produk') }}" />
                                <x-core::input-error for="title" />
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <x-core::date-picker :label="__('Mulai')" mode="datetime" model="start_date" live />
                                    <x-core::input-error for="start_date" />
                                </div>
                                <div>
                                    <x-core::date-picker :label="__('Selesai')" mode="datetime" model="end_date" />
                                    <x-core::input-error for="end_date" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                <div>
                                    <x-core::input label="{{ __('Lokasi') }}" name="location" wire:model="location"
                                        placeholder="{{ __('Ruang rapat A, Online, ...') }}" />
                                    <x-core::input-error for="location" />
                                </div>
                                <div>
                                    <x-core::input label="{{ __('Kategori') }}" name="category" wire:model="category"
                                        placeholder="{{ __('rapat, kegiatan, ...') }}" />
                                    <x-core::input-error for="category" />
                                </div>
                            </div>

                            <div>
                                <x-core::textarea label="{{ __('Deskripsi') }}" name="description"
                                    wire:model="description" rows="3" />
                                <x-core::input-error for="description" />
                            </div>

                            <div class="flex flex-wrap items-center gap-6">
                                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                    <x-core::checkbox wire:model="all_day" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Sepanjang hari') }}</span>
                                </label>
                                <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                    <x-core::checkbox wire:model="is_visible" />
                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Terlihat') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- ─── Recurrence Builder ─────────────────────────── --}}
                    <div class="mb-6 overflow-hidden bg-white border border-gray-200 rounded-2xl shadow-sm dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center gap-3 px-6 py-4 bg-purple-50/60 dark:bg-purple-900/10 border-b border-purple-100 dark:border-purple-900/30">
                            <div class="p-2 bg-purple-100 rounded-lg dark:bg-purple-900/30">
                                <x-lucide-repeat class="w-4 h-4 text-purple-600 dark:text-purple-400" />
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">{{ __('Pengulangan') }}</h3>
                                <p class="text-[11px] text-gray-500 dark:text-gray-400">{{ __('Bangun jadwal berulang (RRULE)') }}</p>
                            </div>
                        </div>

                        <div class="p-6 space-y-5">
                            <label class="inline-flex items-center gap-2.5 cursor-pointer select-none">
                                <x-core::checkbox wire:model.live="is_recurring" />
                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ __('Acara berulang') }}</span>
                            </label>

                            @if($is_recurring)
                                {{-- Frekuensi & interval --}}
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div>
                                        <x-core::select-dropdown :label="__('Frekuensi')" :items="$frequencyOptions"
                                            model="frequency" labelField="label" valueField="value" />
                                        <x-core::input-error for="frequency" />
                                    </div>
                                    <div>
                                        <x-core::input label="{{ __('Interval') }}" type="number" name="interval"
                                            wire:model.live="interval" min="1" max="999" />
                                        <x-core::input-error for="interval" />
                                    </div>
                                </div>

                                {{-- Mode bulanan --}}
                                @if($frequency === 'monthly')
                                    <div>
                                        <x-core::select-dropdown :label="__('Mode bulanan')" :items="$monthlyModeOptions"
                                            model="monthly_mode" labelField="label" valueField="value" />
                                        <x-core::input-error for="monthly_mode" />
                                    </div>
                                @endif

                                {{-- Hari dalam minggu (weekly & monthly-nth) --}}
                                @if($frequency === 'weekly' || ($frequency === 'monthly' && $monthly_mode === 'nth'))
                                    <div>
                                        <x-core::label :value="__('Hari dalam minggu')" />
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($weekDays as $day)
                                                <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-xl cursor-pointer select-none dark:border-gray-600 has-checked:border-purple-400 dark:has-checked:border-purple-600 has-checked:bg-purple-50/60 dark:has-checked:bg-purple-900/20 transition-colors">
                                                    <x-core::checkbox value="{{ $day['value'] }}" wire:model.live="weeklyDays" />
                                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $day['label'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Posisi ke-n dalam bulan (monthly-nth) --}}
                                @if($frequency === 'monthly' && $monthly_mode === 'nth')
                                    <div>
                                        <x-core::label :value="__('Posisi dalam bulan')" />
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($nthPositionOptions as $position)
                                                <label class="inline-flex items-center gap-2 px-3 py-2 border border-gray-200 rounded-xl cursor-pointer select-none dark:border-gray-600 has-checked:border-purple-400 dark:has-checked:border-purple-600 has-checked:bg-purple-50/60 dark:has-checked:bg-purple-900/20 transition-colors">
                                                    <x-core::checkbox value="{{ $position['value'] }}" wire:model.live="nthPositions" />
                                                    <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $position['label'] }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Akhir kejadian --}}
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div>
                                        <x-core::select-dropdown :label="__('Selesai')" :items="$endModeOptions"
                                            model="end_mode" labelField="label" valueField="value" />
                                        <x-core::input-error for="end_mode" />
                                    </div>
                                    @if($end_mode === 'on')
                                        <div>
                                            <x-core::date-picker :label="__('Sampai')" mode="date" model="until" live />
                                            <x-core::input-error for="until" />
                                        </div>
                                    @elseif($end_mode === 'after')
                                        <div>
                                            <x-core::input label="{{ __('Jumlah kejadian') }}" type="number" name="count"
                                                wire:model.live="count" min="1" />
                                            <x-core::input-error for="count" />
                                        </div>
                                    @endif
                                </div>

                                {{-- Preview ringkasan --}}
                                <div class="flex items-start gap-3 p-4 border border-purple-100 rounded-xl bg-purple-50/50 dark:bg-purple-900/10 dark:border-purple-900/30">
                                    <x-lucide-calendar-clock class="w-4 h-4 mt-0.5 text-purple-600 dark:text-purple-400" />
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold tracking-widest text-gray-500 uppercase dark:text-gray-400">{{ __('Ringkasan') }}</p>
                                        <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $this->preview }}</p>
                                        @if(! $this->rruleValid)
                                            <p class="mt-1 text-xs text-red-600 dark:text-red-400">
                                                {{ __('Pilih minimal satu hari (dan posisi untuk bulanan), lalu tinjau ringkasan di atas.') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <x-core::secondary-button label="{{ __('Batal') }}" link :href="route('bale.cms.agendas.index')" />
                        <x-core::button type="submit" label="{{ __('Buat Agenda') }}" spinner="store"
                            :disabled="! $this->rruleValid" />
                    </div>
                </form>
            </div>
        </div>
    </x-core::page-container>
</div>
{{-- Auto-Save Splash Notification --}}
<div x-data="{ showAutoSaveSplash: true }" 
     x-init="setTimeout(() => showAutoSaveSplash = false, 5000)"
     x-show="showAutoSaveSplash"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 transform translate-x-12"
     x-transition:enter-end="opacity-100 transform translate-x-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     class="fixed bottom-6 right-6 z-60 w-full max-w-sm"
     style="display: none">
    <div class="relative overflow-hidden bg-white/90 dark:bg-gray-800/90 backdrop-blur-xl border border-emerald-200 dark:border-emerald-800/50 rounded-2xl shadow-2xl p-5 group ring-1 ring-black/5 dark:ring-white/5">
        {{-- Close Button --}}
        <button @click="showAutoSaveSplash = false" class="absolute top-3 right-3 p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700/50 rounded-lg transition-all">
            <x-lucide-x class="w-4 h-4" />
        </button>

        <div class="flex items-start gap-4">
            <div class="p-3 bg-linear-to-br from-emerald-500 to-teal-600 text-white rounded-xl shadow-lg shadow-emerald-500/20">
                <x-lucide-zap class="w-6 h-6 animate-pulse" />
            </div>
            <div class="flex-1 pr-4">
                <h4 class="font-bold text-gray-900 dark:text-white mb-1 flex items-center gap-2">
                    {{ __('Auto-Save Aktif!') }}
                    <span class="px-1.5 py-0.5 bg-emerald-100 dark:bg-emerald-900/40 text-[10px] text-emerald-700 dark:text-emerald-400 uppercase tracking-wider font-extrabold rounded-md">{{ __('New') }}</span>
                </h4>
                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                    {{ __('Setiap perubahan Anda kini disimpan secara otomatis. Tidak perlu lagi menekan tombol simpan manual.') }}
                </p>
            </div>
        </div>

        {{-- Countdown Progress Bar --}}
        <div class="absolute bottom-0 left-0 h-1 bg-emerald-500/20 w-full">
            <div class="h-full bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)] animate-[progress-shrink_5s_linear_forwards]"></div>
        </div>
    </div>
</div>

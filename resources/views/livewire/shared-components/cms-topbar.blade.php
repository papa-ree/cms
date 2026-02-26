<header
    class="sticky top-0 inset-x-0 z-48 w-full border-b backdrop-blur-md bg-white/80 dark:bg-slate-900/80 border-slate-200 dark:border-slate-700/60 lg:pl-64 transition-all duration-300">
    <nav class="flex items-center w-full px-4 sm:px-6 md:px-8 py-2.5 sm:py-3" aria-label="Global">
        {{-- ========== Mobile Actions (Left) ========== --}}
        <div class="flex items-center gap-x-3 lg:hidden">
            <button type="button"
                class="inline-flex items-center justify-center p-2 rounded-lg text-slate-500 hover:text-slate-600 hover:bg-slate-100 dark:text-slate-400 dark:hover:text-slate-300 dark:hover:bg-slate-800 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500"
                data-hs-overlay="#cms-sidebar" aria-controls="cms-sidebar" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <x-lucide-menu class="shrink-0 w-6 h-6" />
            </button>

            {{-- Mobile Tenant Logo/Name --}}
            @if ($this->activeBale)
                <div class="flex items-center gap-2 px-2 py-1 bg-slate-100 dark:bg-slate-800 rounded-lg max-w-[140px] xs:max-w-none">
                    <div class="shrink-0 w-6 h-6 rounded-md bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-sm">
                        <x-lucide-building-2 class="w-3.5 h-3.5 text-white" />
                    </div>
                    <span class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">
                        {{ $this->activeBale->name }}
                    </span>
                </div>
            @endif
        </div>

        {{-- ========== Desktop Content (Center/Left) ========== --}}
        <div class="hidden lg:flex items-center gap-x-4">
           {{-- Reserved for Breadcrumbs or Page Title --}}
           {{-- <h1 class="text-lg font-semibold text-slate-800 dark:text-white">{{ __('Dashboard') }}</h1> --}}
        </div>

        {{-- ========== Right Side Actions ========== --}}
        <div class="flex items-center justify-end flex-1 gap-x-1.5 sm:gap-x-3">
            {{-- Search - Optional --}}
            {{-- <div class="hidden md:block">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <x-lucide-search class="h-4 w-4 text-slate-400" />
                    </div>
                    <input type="text"
                        class="block w-full pl-10 pr-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 dark:bg-slate-800/50 text-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all sm:w-64"
                        placeholder="{{ __('Search...') }}">
                </div>
            </div> --}}

            <div class="flex items-center gap-x-1 sm:gap-x-2">
                {{-- Dark Mode Toggle --}}
                <div class="p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                    <x-core::dark-mode-toggle />
                </div>

                {{-- Locale Dropdown --}}
                <div class="hidden sm:block">
                    <livewire:core.shared-components.locale-dropdown />
                </div>

                {{-- Account Dropdown --}}
                <div class="pl-2 sm:pl-3 border-l border-slate-200 dark:border-slate-700 ml-1">
                    <livewire:core.shared-components.account-dropdown />
                </div>
            </div>
        </div>
    </nav>
</header>
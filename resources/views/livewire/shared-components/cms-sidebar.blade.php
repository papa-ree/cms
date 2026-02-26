<div>
    {{-- Sidebar --}}
    <div id="cms-sidebar" class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 left-0 bottom-0 z-60 lg:z-50 w-64 overflow-y-auto scrollbar-thin scrollbar-thumb-slate-700 scrollbar-track-transparent lg:block lg:translate-x-0 lg:right-auto lg:bottom-0
               bg-linear-to-b from-slate-900 via-slate-900 to-slate-800
               border-r border-slate-700/60">

        @persist('sidebar-bale-cms')

        {{-- ========== Tenant Info Card ========== --}}
        <div class="px-4 pt-6 pb-4">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                {{-- Avatar / Icon --}}
                <div
                    class="shrink-0 w-10 h-10 rounded-lg bg-linear-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg">
                    <x-lucide-building-2 class="w-5 h-5 text-white" />
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    @if ($this->activeBale)
                        <p class="text-xs font-medium text-slate-400 truncate leading-none mb-0.5">
                            {{ $this->activeBale->organization?->name ?? __('Organization') }}
                        </p>
                        <p class="text-sm font-semibold text-white truncate leading-snug">
                            {{ $this->activeBale->name }}
                        </p>
                    @else
                        <p class="text-xs text-slate-400">{{ __('No active tenant') }}</p>
                    @endif
                </div>

                {{-- Live status dot --}}
                <div class="shrink-0" title="Active">
                    <span class="relative flex h-2.5 w-2.5">
                        <span
                            class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                </div>
            </div>
        </div>

        {{-- ========== Divider with label ========== --}}
        <div class="px-4 mb-2">
            <div class="flex items-center gap-2">
                <div class="h-px flex-1 bg-slate-700/60"></div>
                <span class="text-[10px] uppercase tracking-widest text-slate-500 font-semibold">{{ __('Menu') }}</span>
                <div class="h-px flex-1 bg-slate-700/60"></div>
            </div>
        </div>

        {{-- ========== Navigation ========== --}}
        <nav class="flex flex-col w-full px-3 pb-24" data-hs-accordion-always-open>
            <ul class="space-y-0.5">
                @foreach ($this->availableMenus as $menu)
                    <li>
                        <a href="/cms/{{ $menu['url'] }}" wire:navigate.hover class="group flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium
                                               text-slate-400 hover:text-white hover:bg-white/8
                                               transition-all duration-150 ease-in-out"
                            wire:current="bg-indigo-600/25 border border-indigo-500/40 text-white">

                            {{-- Icon --}}
                            <span
                                class="shrink-0 w-5 h-5 text-slate-500 group-hover:text-indigo-400 transition-colors duration-150">
                                <x-dynamic-component :component="'lucide-' . ($menu['icon'] ?? 'circle')" class="w-5 h-5" />
                            </span>

                            {{-- Label --}}
                            <span class="capitalize tracking-wide">{{ __($menu['label']) }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        {{-- ========== Bottom: Exit CMS ========== --}}
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-slate-700/60 bg-slate-900/90 backdrop-blur-sm">
            <a href="/cms/exit-cms" wire:navigate.hover class="group flex items-center gap-3 w-full px-3 py-2.5 rounded-lg text-sm font-medium
                       text-slate-400 hover:text-rose-400 hover:bg-rose-500/10
                       transition-all duration-150 ease-in-out">
                <x-lucide-log-out
                    class="w-5 h-5 shrink-0 text-slate-500 group-hover:text-rose-400 transition-colors duration-150" />
                <span>{{ __('Exit CMS') }}</span>
            </a>
        </div>

        @endpersist
    </div>
    {{-- End Sidebar --}}
</div>
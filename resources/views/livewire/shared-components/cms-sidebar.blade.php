<div>
    {{-- <!-- Sidebar --> --}}
    <div id="cms-sidebar"
        class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 left-0 bottom-0 z-[60] lg:z-[50] w-64 bg-white border-r border-gray-200 pt-7 pb-10 overflow-y-auto scrollbar-y lg:block lg:translate-x-0 lg:right-auto lg:bottom-0 dark:scrollbar-y dark:bg-gray-800 dark:border-gray-700">

        @persist('sidebar-bale-cms')
        <nav class="flex flex-col flex-wrap w-full p-6 " data-hs-accordion-always-open>
            <ul class="space-y-1.5 hs-accordion-group">
                @foreach ($this->availableMenus as $menu)
                    <li>
                        <a href="/cms/{{$menu['url']}}" wire:navigate.hover
                            class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                            wire:current="bg-gray-100 dark:bg-gray-900">{{ $menu['label'] }}</a>
                    </li>
                @endforeach

                <li>
                    <a href="/cms/exit-cms" wire:navigate.hover
                        class="flex capitalize items-center gap-x-3.5 py-2 px-2.5 text-sm text-slate-700 rounded-md hover:bg-gray-200 hover:text-slate-800 duration-200 ease-in-out transition-all hover:dark:bg-gray-900 dark:text-white"
                        wire:current="bg-gray-100 dark:bg-gray-900">Exit CMS</a>
                </li>
            </ul>
        </nav>
        @endpersist

    </div>
    {{-- <!-- End Sidebar --> --}}
</div>
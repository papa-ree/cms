<div>
    {{-- Hero Section --}}
    <div class="relative overflow-hidden p-8 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);" data-aos="fade-up">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="mb-6 md:mb-0">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-white/20 backdrop-blur-md rounded-xl">
                        <x-lucide-layout class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white md:text-4xl">Section Management</h1>
                    </div>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">
                    Manage page sections and layouts for dynamic content organization.
                </p>
            </div>
            <div class="shrink-0">
                <x-core::button link href="{{ route('bale.cms.sections.create') }}" label="Create Section"
                    class="gap-x-2 bg-white text-purple-600 hover:bg-white/90">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-5 h-5" />
                    </x-slot>
                </x-core::button>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
        <livewire:cms.pages.section.section.section-table />
    </div>
</div>
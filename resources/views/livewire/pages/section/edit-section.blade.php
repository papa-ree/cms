<div>
    {{-- Hero Section --}}
    <div class="relative overflow-hidden p-6 mb-8 text-white rounded-2xl shadow-xl"
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);">
        <div class="absolute top-0 right-0 w-48 h-48 bg-white/10 rounded-full -mr-24 -mt-24"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-white/10 rounded-full -ml-16 -mb-16"></div>

        <div class="relative z-10">
            <a href="{{ route('bale.cms.sections.index') }}"
                class="inline-flex items-center gap-2 text-white/90 hover:text-white mb-4 transition-colors">
                <x-lucide-arrow-left class="w-4 h-4" />
                <span class="text-sm">Back to Sections</span>
            </a>
            <div class="flex items-center gap-3">
                <div class="p-2.5 bg-white/20 backdrop-blur-md rounded-lg">
                    <x-lucide-layout class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white">Edit Section</h1>
                    <p class="text-white/80 text-sm mt-1">{{ $slug }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Section Type Indicator --}}
    <div class="mb-6 p-4 bg-linear-to-r from-purple-50 to-blue-50 dark:from-purple-900/20 dark:to-blue-900/20 border border-purple-200 dark:border-purple-800 rounded-xl">
        <div class="flex items-center gap-4">
            <div class="p-3 rounded-xl shadow-md {{
                $slug == 'hero-section' ? 'bg-linear-to-br from-indigo-500 to-indigo-600' :
                ($slug == 'post-section' ? 'bg-linear-to-br from-emerald-500 to-emerald-600' :
                'bg-linear-to-br from-purple-500 to-purple-600')
            }}">
                @if($slug == 'hero-section')
                    <x-lucide-sparkles class="w-6 h-6 text-white" />
                @elseif($slug == 'post-section')
                    <x-lucide-newspaper class="w-6 h-6 text-white" />
                @else
                    <x-lucide-box class="w-6 h-6 text-white" />
                @endif
            </div>
            <div class="flex-1">
                <div class="flex items-center gap-2 mb-1">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                        @if($slug == 'hero-section')
                            Hero Section Configuration
                        @elseif($slug == 'post-section')
                            Post Section Configuration
                        @else
                            Extension Section Configuration
                        @endif
                    </h3>
                    <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full {{
                        $slug == 'hero-section' ? 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' :
                        ($slug == 'post-section' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300' :
                        'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300')
                    }}">
                        @if($slug == 'hero-section')
                            Hero
                        @elseif($slug == 'post-section')
                            Post
                        @else
                            Extension
                        @endif
                    </span>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    @if($slug == 'hero-section')
                        Configure the hero banner section for your page
                    @elseif($slug == 'post-section')
                        Manage post listings and content sections
                    @else
                        Customize extension section settings
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Form Content --}}
    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
        <div class="mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <x-lucide-settings class="w-4 h-4" />
                <span>Section Settings</span>
            </div>
        </div>

        @if ($slug == 'hero-section')
            <livewire:cms.pages.section.section.hero-section-form :slug="$slug" />
        @elseif ($slug == 'post-section')
            <livewire:cms.pages.section.section.post-section-form :slug="$slug" />
        @else
            <livewire:cms.pages.section.section.extension-section-form :slug="$slug" />
        @endif
    </div>
</div>
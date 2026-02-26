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
                        <x-lucide-layout-dashboard class="w-8 h-8 text-white" />
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white md:text-4xl">{{ __('Dashboard Overview') }}</h1>
                    </div>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">
                    {{ __('Monitor your content performance and website traffic in real-time.') }}
                </p>
            </div>
            <div class="shrink-0">
                <x-core::button link href="{{ route('bale.cms.posts.create') }}" label="{{ __('Create New Post') }}"
                    class="gap-x-2 bg-white text-purple-600 hover:bg-white/90">
                    <x-slot name="icon">
                        <x-lucide-plus class="w-5 h-5" />
                    </x-slot>
                </x-core::button>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" data-aos="fade-up" data-aos-delay="100">
        {{-- Total Posts --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg">
                    <x-lucide-file-text class="w-6 h-6 text-white" />
                </div>
                <span
                    class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">{{ __('Content') }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Posts') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $internalStats['total_posts'] }}
                </p>
            </div>
            <div class="mt-4 flex items-center text-sm gap-2">
                <span class="text-green-600 dark:text-green-400 flex items-center gap-1 font-medium">
                    <x-lucide-check-circle class="w-4 h-4" />
                    {{ $internalStats['published_posts'] }}
                </span>
                <span class="text-gray-300">•</span>
                <span class="text-amber-600 dark:text-amber-400 flex items-center gap-1 font-medium">
                    <x-lucide-clock class="w-4 h-4" />
                    {{ $internalStats['draft_posts'] }}
                </span>
            </div>
        </div>

        {{-- Total Pages --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                    <x-lucide-file class="w-6 h-6 text-white" />
                </div>
                <span
                    class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full dark:bg-purple-900/50 dark:text-purple-300">{{ __('Pages') }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Pages') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $internalStats['total_pages'] }}
                </p>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Static content pages') }}</p>
            </div>
        </div>

        {{-- Visitors (Umami) --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                    <x-lucide-users class="w-6 h-6 text-white" />
                </div>
                @if($externalStats['unavailable'])
                    <span
                        class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300 flex items-center gap-1">
                        <x-lucide-wifi-off class="w-3 h-3" />
                        {{ __('Unavailable') }}
                    </span>
                @else
                    <span
                        class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">{{ __('7 days') }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Visitors') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $externalStats['unavailable'] ? '—' : number_format($externalStats['overview']['total_visitors']) }}
                </p>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500 dark:text-gray-400">
                {{ __('Bounce') }}: <span
                    class="ml-1 font-semibold text-gray-700 dark:text-gray-300">{{ $externalStats['overview']['bounce_rate'] }}</span>
            </div>
        </div>

        {{-- Avg Session (Umami) --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                    <x-lucide-timer class="w-6 h-6 text-white" />
                </div>
                @if($externalStats['unavailable'])
                    <span
                        class="px-3 py-1 text-xs font-semibold text-red-600 bg-red-100 rounded-full dark:bg-red-900/50 dark:text-red-300 flex items-center gap-1">
                        <x-lucide-wifi-off class="w-3 h-3" />
                        {{ __('Unavailable') }}
                    </span>
                @else
                    <span
                        class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full dark:bg-amber-900/50 dark:text-amber-300">{{ __('Avg') }}</span>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Session Duration') }}</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $externalStats['overview']['avg_session_duration'] }}
                </p>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Average time on site') }}</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="grid grid-cols-1 gap-4 mb-8 md:grid-cols-3" data-aos="fade-up" data-aos-delay="150">
        <a href="{{ route('bale.cms.posts.create') }}"
            class="group p-5 bg-linear-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-2xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-blue-600 rounded-xl">
                    <x-lucide-plus class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('New Post') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Create content') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('bale.cms.pages.index') }}"
            class="group p-5 bg-linear-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 border border-purple-200 dark:border-purple-800 rounded-2xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-purple-600 rounded-xl">
                    <x-lucide-file class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('Manage Pages') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('View all pages') }}</p>
                </div>
            </div>
        </a>

        <a href="{{ route('bale.cms.navigations.index') }}"
            class="group p-5 bg-linear-to-br from-emerald-50 to-emerald-100 dark:from-emerald-900/20 dark:to-emerald-800/20 border border-emerald-200 dark:border-emerald-800 rounded-2xl hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-emerald-600 rounded-xl">
                    <x-lucide-menu class="w-6 h-6 text-white" />
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ __('Navigation') }}</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Edit menus') }}</p>
                </div>
            </div>
        </a>
    </div>

    {{-- Charts and Recent Posts --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6" data-aos="fade-up" data-aos-delay="200">
        {{-- Chart Section --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                        {{ __('Traffic Overview') }}
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Last 7 days analytics') }}</p>
                </div>
                <div class="p-3 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                    <x-lucide-trending-up class="w-6 h-6 text-white" />
                </div>
            </div>
            @if($externalStats['unavailable'])
                <div class="flex flex-col items-center justify-center h-[300px] text-center">
                    <div class="mb-3 p-4 rounded-full bg-gray-100 dark:bg-gray-700">
                        <x-lucide-wifi-off class="w-8 h-8 text-gray-400 dark:text-gray-500" />
                    </div>
                    <p class="text-sm font-semibold text-gray-500 dark:text-gray-400">{{ __('Analytics Unavailable') }}</p>
                    <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">
                        {{ __('Could not connect to analytics service.') }}
                    </p>
                </div>
            @else
                    <x-core::chart type="line" :labels="$externalStats['chart']['labels']" :datasets="[
                    [
                        'label' => __('Visitors'),
                        'data' => $externalStats['chart']['visitors'],
                        'borderColor' => '#3b82f6',
                        'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                        'borderWidth' => 2,
                        'fill' => true,
                        'tension' => 0.4,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 6,
                    ],
                    [
                        'label' => __('Page Views'),
                        'data' => $externalStats['chart']['page_views'],
                        'borderColor' => '#10b981',
                        'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                        'borderWidth' => 2,
                        'fill' => true,
                        'tension' => 0.4,
                        'pointRadius' => 3,
                        'pointHoverRadius' => 6,
                    ],
                ]" class="h-[300px]" />
            @endif
        </div>

        {{-- Recent Posts --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Recent Updates') }}</h3>
                <div class="p-2 bg-linear-to-br from-pink-500 to-pink-600 rounded-lg shadow-lg">
                    <x-lucide-sparkles class="w-5 h-5 text-white" />
                </div>
            </div>
            <div class="space-y-4">
                @forelse($recentPosts as $post)
                    <div
                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        @if($post->thumbnail)
                            <img src="{{ $post->thumbnail_url }}" alt="" class="w-12 h-12 rounded-lg object-cover shrink-0">
                        @else
                            <div
                                class="w-12 h-12 rounded-lg bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shrink-0">
                                <x-lucide-image class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('bale.cms.posts.edit', $post->slug) }}"
                                class="block text-sm font-semibold text-gray-900 dark:text-gray-100 truncate hover:text-purple-600 dark:hover:text-purple-400 transition-colors">
                                {{ $post->title }}
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                <x-lucide-clock class="w-3 h-3" />
                                {{ $post->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <div
                            class="w-16 h-16 mx-auto mb-3 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <x-lucide-inbox class="w-8 h-8 text-gray-400" />
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ __('No recent posts') }}</p>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('bale.cms.posts.index') }}"
                class="block mt-6 text-center text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                {{ __('View All Posts') }}
                <x-lucide-arrow-right class="w-4 h-4 inline ml-1" />
            </a>
        </div>
    </div>
</div>

{{-- Chart.js dimuat oleh x-chart component dari bale-core --}}
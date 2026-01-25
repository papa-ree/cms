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
                        <h1 class="text-3xl font-bold text-white md:text-4xl">Dashboard Overview</h1>
                    </div>
                </div>
                <p class="max-w-2xl text-white/90 text-lg">
                    Monitor your content performance and website traffic in real-time.
                </p>
            </div>
            <div class="shrink-0">
                <x-core::button link href="{{ route('bale.cms.posts.create') }}" label="Create New Post"
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
                    class="px-3 py-1 text-xs font-semibold text-blue-700 bg-blue-100 rounded-full dark:bg-blue-900/50 dark:text-blue-300">Content</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Posts</p>
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
                    class="px-3 py-1 text-xs font-semibold text-purple-700 bg-purple-100 rounded-full dark:bg-purple-900/50 dark:text-purple-300">Pages</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pages</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $internalStats['total_pages'] }}
                </p>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Static content pages</p>
            </div>
        </div>

        {{-- Visitors (GA) --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-green-500 to-green-600 rounded-xl shadow-lg">
                    <x-lucide-users class="w-6 h-6 text-white" />
                </div>
                <span
                    class="px-3 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full dark:bg-green-900/50 dark:text-green-300">7
                    days</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Visitors (Dummy Data)</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $externalStats['overview']['total_visitors'] }}
                </p>
            </div>
            <div class="mt-4 flex items-center text-xs text-gray-500 dark:text-gray-400">
                Bounce: <span
                    class="ml-1 font-semibold text-gray-700 dark:text-gray-300">{{ $externalStats['overview']['bounce_rate'] }}</span>
            </div>
        </div>

        {{-- Avg Session (GA) --}}
        <div
            class="group p-6 transition-all duration-300 bg-white border border-gray-100 shadow-md dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700 hover:-translate-y-1">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-linear-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg">
                    <x-lucide-timer class="w-6 h-6 text-white" />
                </div>
                <span
                    class="px-3 py-1 text-xs font-semibold text-amber-700 bg-amber-100 rounded-full dark:bg-amber-900/50 dark:text-amber-300">Avg</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Session Duration</p>
                <p class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">
                    {{ $externalStats['overview']['avg_session_duration'] }}
                </p>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-500 dark:text-gray-400">Average time on site</p>
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
                    <h3 class="font-semibold text-gray-900 dark:text-white">New Post</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Create content</p>
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
                    <h3 class="font-semibold text-gray-900 dark:text-white">Manage Pages</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">View all pages</p>
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
                    <h3 class="font-semibold text-gray-900 dark:text-white">Navigation</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Edit menus</p>
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
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Traffic Overview (Dummy Data)</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Last 7 days analytics</p>
                </div>
                <div class="p-3 bg-linear-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg">
                    <x-lucide-trending-up class="w-6 h-6 text-white" />
                </div>
            </div>
            <div class="w-full h-[300px]">
                <canvas id="traffic-chart"></canvas>
            </div>
        </div>

        {{-- Recent Posts --}}
        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Updates</h3>
                <div class="p-2 bg-linear-to-br from-pink-500 to-pink-600 rounded-lg shadow-lg">
                    <x-lucide-sparkles class="w-5 h-5 text-white" />
                </div>
            </div>
            <div class="space-y-4">
                @forelse($recentPosts as $post)
                    <div
                        class="flex items-start gap-3 p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        @if($post->thumbnail)
                            <img src="{{ $post->thumbnail_url }}" alt=""
                                class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div
                                class="w-12 h-12 rounded-lg bg-linear-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center flex-shrink-0">
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
                        <p class="text-sm text-gray-500 dark:text-gray-400">No recent posts</p>
                    </div>
                @endforelse
            </div>

            <a href="{{ route('bale.cms.posts.index') }}"
                class="block mt-6 text-center text-sm font-semibold text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 transition-colors">
                View All Posts
                <x-lucide-arrow-right class="w-4 h-4 inline ml-1" />
            </a>
        </div>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    document.addEventListener( 'livewire:initialized', () =>
    {
        const ctx = document.getElementById( 'traffic-chart' );

        // Function to get current theme colors
        const getChartColors = () =>
        {
            const isDark = document.documentElement.classList.contains( 'dark' );
            return {
                text: isDark ? '#e2e8f0' : '#475569',
                grid: isDark ? '#334155' : '#f1f5f9',
                bg: isDark ? '#1e293b' : '#ffffff',
                tooltipTitle: isDark ? '#f8fafc' : '#0f172a',
                tooltipBody: isDark ? '#e2e8f0' : '#475569',
                tooltipBorder: isDark ? '#334155' : '#e2e8f0'
            };
        };

        let colors = getChartColors();

        const chart = new Chart( ctx, {
            type: 'line',
            data: {
                labels: @json($externalStats['chart']['labels']),
                datasets: [
                    {
                        label: 'Visitors',
                        data: @json($externalStats['chart']['visitors']),
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Page Views',
                        data: @json($externalStats['chart']['page_views']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: colors.text,
                            font: {
                                family: 'inherit',
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: colors.bg,
                        titleColor: colors.tooltipTitle,
                        bodyColor: colors.tooltipBody,
                        borderColor: colors.tooltipBorder,
                        borderWidth: 1,
                        padding: 10,
                        titleFont: { size: 13, weight: 'bold' },
                        bodyFont: { size: 12 }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: colors.grid,
                            drawBorder: false
                        },
                        ticks: {
                            color: colors.text,
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: colors.text,
                            font: { size: 11 }
                        }
                    }
                },
                interaction: {
                    mode: 'nearest',
                    axis: 'x',
                    intersect: false
                }
            }
        } );

        // Watch for dark mode changes to update chart
        const observer = new MutationObserver( ( mutations ) =>
        {
            mutations.forEach( ( mutation ) =>
            {
                if ( mutation.attributeName === 'class' ) {
                    const newColors = getChartColors();

                    chart.options.plugins.legend.labels.color = newColors.text;
                    chart.options.plugins.tooltip.backgroundColor = newColors.bg;
                    chart.options.plugins.tooltip.titleColor = newColors.tooltipTitle;
                    chart.options.plugins.tooltip.bodyColor = newColors.tooltipBody;
                    chart.options.plugins.tooltip.borderColor = newColors.tooltipBorder;
                    chart.options.scales.y.grid.color = newColors.grid;
                    chart.options.scales.y.ticks.color = newColors.text;
                    chart.options.scales.x.ticks.color = newColors.text;

                    chart.update();
                }
            } );
        } );

        observer.observe( document.documentElement, {
            attributes: true
        } );
    } );
</script>
@endscript
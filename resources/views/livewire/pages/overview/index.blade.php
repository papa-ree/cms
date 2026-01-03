<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                Dashboard Overview
            </h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                Monitor your content performance and website traffic.
            </p>
        </div>

        <x-core::button link href="{{ route('bale.cms.posts.create') }}" label="New Post" class="gap-x-2">
            <x-slot name="icon">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </x-slot>
        </x-core::button>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Total Posts --}}
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Posts</h3>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ $internalStats['total_posts'] }}</h4>
                </div>
                <div class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                        </path>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="text-green-500 flex items-center gap-1 font-medium">
                    {{ $internalStats['published_posts'] }} Published
                </span>
                <span class="mx-2 text-gray-300">|</span>
                <span class="text-amber-500 flex items-center gap-1 font-medium">
                    {{ $internalStats['draft_posts'] }} Drafts
                </span>
            </div>
        </div>

        {{-- Total Pages --}}
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Pages</h3>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ $internalStats['total_pages'] }}</h4>
                </div>
                <div class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Static content pages</p>
        </div>

        {{-- Visitors (GA) --}}
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Visitors (7d)</h3>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ $externalStats['overview']['total_visitors'] }}</h4>
                </div>
                <div class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4 flex items-center gap-1">
                Bounce Rate: <span
                    class="font-semibold text-gray-700 dark:text-gray-300">{{ $externalStats['overview']['bounce_rate'] }}</span>
            </p>
        </div>

        {{-- Avg Session (GA) --}}
        <div class="bg-white dark:bg-slate-800 p-5 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <div class="flex justify-between items-start">
                <div>
                    <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium">Avg. Session</h3>
                    <h4 class="text-2xl font-bold text-gray-800 dark:text-white mt-2">
                        {{ $externalStats['overview']['avg_session_duration'] }}</h4>
                </div>
                <div class="p-2 bg-orange-50 dark:bg-orange-900/30 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Average time on site</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Chart Section --}}
        <div
            class="lg:col-span-2 bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Traffic Overview (Coming Soon)</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">Dummy Data</p>
            <div class="w-full h-[300px]">
                <canvas id="traffic-chart"></canvas>
            </div>
        </div>

        {{-- Recent Posts list --}}
        <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-slate-700">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Recent Updates</h3>
            <div class="space-y-4">
                @forelse($recentPosts as $post)
                    <div class="flex items-start gap-3">
                        @if($post->thumbnail)
                            <img src="{{ Storage::disk('s3')->url(session('bale_active_slug') . '/thumbnails/' . $post->thumbnail) }}"
                                alt="" class="w-12 h-12 rounded-lg object-cover flex-shrink-0 bg-gray-100">
                        @else
                            <div
                                class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0 text-gray-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        @endif
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('bale.cms.posts.edit', $post->slug) }}"
                                class="block text-sm font-medium text-gray-800 dark:text-gray-200 truncate hover:text-blue-600 transition-colors">
                                {{ $post->title }}
                            </a>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                {{ $post->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No recent posts found.</p>
                @endforelse
            </div>

            <a href="{{ route('bale.cms.posts.index') }}"
                class="block mt-6 text-center text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                View All Posts &rarr;
            </a>
        </div>
    </div>
</div>

@assets
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endassets

@script
<script>
    document.addEventListener('livewire:initialized', () => {
        const ctx = document.getElementById('traffic-chart');
        
        // Function to get current theme colors
        const getChartColors = () => {
            const isDark = document.documentElement.classList.contains('dark');
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

        const chart = new Chart(ctx, {
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
        });

        // Watch for dark mode changes to update chart
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
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
            });
        });

        observer.observe(document.documentElement, {
            attributes: true
        });
    });
</script>
@endscript

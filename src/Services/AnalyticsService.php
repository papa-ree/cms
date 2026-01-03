<?php

namespace Bale\Cms\Services;

use Bale\Cms\Models\Page;
use Bale\Cms\Models\Post;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Get internal CMS statistics.
     */
    public function getInternalStats(): array
    {
        TenantConnectionService::ensureActive();

        return [
            'total_posts' => Post::count(),
            'published_posts' => Post::where('published', true)->count(),
            'draft_posts' => Post::where('published', false)->count(),
            'total_pages' => Page::count(),
        ];
    }

    /**
     * Get recent posts for dashboard widget.
     */
    public function getRecentPosts(int $limit = 5)
    {
        TenantConnectionService::ensureActive();

        return Post::latest('updated_at')
            ->take($limit)
            ->get();
    }

    /**
     * Get external Google Analytics statistics (Mock).
     * Replace with real GA4 API implementation later.
     */
    public function getExternalStats(): array
    {
        // Mock data for chart (Last 7 days)
        $dates = [];
        $visitors = [];
        $pageViews = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M d');
            $visitors[] = rand(100, 500);
            $pageViews[] = rand(200, 1000);
        }

        return [
            'overview' => [
                'total_visitors' => array_sum($visitors),
                'total_page_views' => array_sum($pageViews),
                'bounce_rate' => rand(30, 60) . '%',
                'avg_session_duration' => '2m ' . rand(10, 50) . 's',
            ],
            'chart' => [
                'labels' => $dates,
                'visitors' => $visitors,
                'page_views' => $pageViews,
            ],
            'top_pages' => [
                ['path' => '/', 'views' => rand(1000, 5000)],
                ['path' => '/about', 'views' => rand(500, 2000)],
                ['path' => '/contact', 'views' => rand(100, 1000)],
                ['path' => '/blog/post-1', 'views' => rand(50, 500)],
            ]
        ];
    }
}

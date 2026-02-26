<?php

namespace Bale\Cms\Services;

use Bale\Core\Services\UmamiService;
use Bale\Cms\Models\Page;
use Bale\Cms\Models\Post;

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
     * Get external analytics statistics via Umami Analytics API.
     *
     * Returns real data from Umami if configured and reachable.
     * Falls back to an "unavailable" state if Umami is not configured or is down,
     * without throwing exceptions.
     */
    public function getExternalStats(int $days = 7): array
    {
        $umami = new UmamiService();

        $stats = $umami->getStats($days);
        $pageviews = $umami->getPageviews($days);

        // Jika Umami tidak bisa dihubungi atau belum dikonfigurasi, return unavailable state
        if (is_null($stats) || is_null($pageviews)) {
            return $this->unavailableStats();
        }

        // Mapping data Umami ke format yang digunakan oleh view
        return [
            'unavailable' => false,
            'overview' => $this->mapOverview($stats),
            'chart' => $this->mapChart($pageviews, $days),
        ];
    }

    /**
     * Mapping stats response dari Umami ke format overview.
     *
     * Umami /api/websites/{id}/stats mengembalikan:
     * {
     *   "pageviews": {"value": 123, "change": 45},
     *   "visitors":  {"value": 78,  "change": 10},
     *   "visits":    {"value": 90,  "change": 12},
     *   "bounces":   {"value": 30,  "change": -5},
     *   "totaltime": {"value": 12345, "change": 10}
     * }
     */
    protected function mapOverview(array $stats): array
    {
        $visitors = data_get($stats, 'visitors.value', data_get($stats, 'visitors', 0));
        $pageViews = data_get($stats, 'pageviews.value', data_get($stats, 'pageviews', 0));
        $visits = data_get($stats, 'visits.value', data_get($stats, 'visits', 0));
        $bounces = data_get($stats, 'bounces.value', data_get($stats, 'bounces', 0));
        $totaltime = data_get($stats, 'totaltime.value', data_get($stats, 'totaltime', 0));

        // Bounce rate = (bounces / visits) * 100
        $bounceRate = $visits > 0
            ? round(($bounces / $visits) * 100, 1) . '%'
            : 'N/A';

        // Avg session duration = totaltime (detik) / visits
        $avgSeconds = $visits > 0 ? intdiv($totaltime, $visits) : 0;
        $avgDuration = $avgSeconds > 0
            ? intdiv($avgSeconds, 60) . 'm ' . ($avgSeconds % 60) . 's'
            : 'N/A';

        return [
            'total_visitors' => $visitors,
            'total_page_views' => $pageViews,
            'bounce_rate' => $bounceRate,
            'avg_session_duration' => $avgDuration,
        ];
    }

    /**
     * Mapping pageviews response dari Umami ke format chart.
     *
     * Umami /api/websites/{id}/pageviews mengembalikan:
     * {
     *   "pageviews": [{"x": "2024-01-01", "y": 120}, ...],
     *   "sessions":  [{"x": "2024-01-01", "y":  78}, ...]
     * }
     */
    protected function mapChart(array $pageviews, int $days): array
    {
        $timezone = config('core.analytics.umami.timezone', 'Asia/Jakarta');

        // Buat map dari data Umami untuk lookup cepat
        $pvMap = collect(data_get($pageviews, 'pageviews', []))->keyBy(function ($item) {
            return substr($item['x'], 0, 10);
        });
        $sessionMap = collect(data_get($pageviews, 'sessions', []))->keyBy(function ($item) {
            return substr($item['x'], 0, 10);
        });

        $labels = [];
        $visitors = [];
        $chartPageViews = [];

        for ($i = $days - 1; $i >= 0; $i--) {
            // Tanggal harus dihitung dalam timezone yang sama dengan yang dikirim ke Umami API
            $day = now($timezone)->subDays($i);
            $date = $day->format('Y-m-d');  // key lookup ke Umami response
            $label = $day->format('M d');    // label di chart

            $labels[] = $label;
            $visitors[] = (int) data_get($sessionMap->get($date), 'y', 0);
            $chartPageViews[] = (int) data_get($pvMap->get($date), 'y', 0);
        }

        return [
            'labels' => $labels,
            'visitors' => $visitors,
            'page_views' => $chartPageViews,
        ];
    }

    /**
     * Struktur data saat Umami tidak tersedia.
     */
    protected function unavailableStats(): array
    {
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('M d');
        }

        return [
            'unavailable' => true,
            'overview' => [
                'total_visitors' => 0,
                'total_page_views' => 0,
                'bounce_rate' => 'N/A',
                'avg_session_duration' => 'N/A',
            ],
            'chart' => [
                'labels' => $labels,
                'visitors' => array_fill(0, 7, 0),
                'page_views' => array_fill(0, 7, 0),
            ],
        ];
    }
}

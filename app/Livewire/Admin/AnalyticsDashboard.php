<?php

namespace App\Livewire\Admin;

use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.admin')]
class AnalyticsDashboard extends Component
{
    public string $dateRange = '7days';

    public ?string $errorMessage = null;

    public string $deviceFilter = 'all';

    public string $browserFilter = 'all';

    public string $osFilter = 'all';

    public function updatingDateRange()
    {
        // Reset pagination or any state if needed
    }

    public function getDateRange(): array
    {
        $endDate = Carbon::now();
        $startDate = match ($this->dateRange) {
            '30days' => $endDate->clone()->subDays(30),
            '90days' => $endDate->clone()->subDays(90),
            default => $endDate->clone()->subDays(7),
        };

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
        ];
    }

    public function getVisitorsData(): array
    {
        $dateRange = $this->getDateRange();
        $startDate = $dateRange['startDate'];
        $endDate = $dateRange['endDate'];

        // Build an array of keys for all days in the range to ensure continuous chart
        $labels = [];
        $visitors = [];
        $pageViews = [];

        for ($date = clone $startDate; $date->lte($endDate); $date->addDay()) {
            $key = $date->format('Ymd');
            $labels[$key] = $date->format('M d');
            $visitors[$key] = 0;
            $pageViews[$key] = 0;
        }

        $data = GoogleAnalyticsService::getVisitors($startDate, $endDate);

        foreach ($data as $item) {
            $key = $item['date'];
            if (isset($visitors[$key])) {
                $visitors[$key] = $item['visitors'] ?? 0;
                $pageViews[$key] = $item['pageViews'] ?? 0;
            }
        }

        return [
            'labels' => array_values($labels),
            'visitors' => array_values($visitors),
            'pageViews' => array_values($pageViews),
        ];
    }

    public function getPageViewsData(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getPageViews($dateRange['startDate'], $dateRange['endDate']);

        return array_slice(array_map(fn ($item) => [
            'url' => $item['url'] ?? 'N/A',
            'views' => $item['pageViews'] ?? 0,
        ], $data), 0, 10);
    }

    public function getBrowserData(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getBrowserData($dateRange['startDate'], $dateRange['endDate']);

        return array_slice(array_map(fn ($item) => [
            'name' => $item['name'] ?? 'Unknown',
            'users' => $item['users'] ?? 0,
        ], $data), 0, 8);
    }

    public function getOSData(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getOSData($dateRange['startDate'], $dateRange['endDate']);

        return array_slice(array_map(fn ($item) => [
            'name' => $item['name'] ?? 'Unknown',
            'users' => $item['users'] ?? 0,
        ], $data), 0, 8);
    }

    public function getTotalStats(): array
    {
        $dateRange = $this->getDateRange();
        $totalUsers = GoogleAnalyticsService::getTotalUsers($dateRange['startDate'], $dateRange['endDate']);
        $totalViews = GoogleAnalyticsService::getTotalPageViews($dateRange['startDate'], $dateRange['endDate']);

        return [
            'totalVisitors' => $totalUsers,
            'totalPageViews' => $totalViews,
            'avgPageViews' => round($totalViews / max($totalUsers, 1), 2),
        ];
    }

    public function render()
    {
        $this->errorMessage = null;

        try {
            $pageViews = $this->getPageViewsData();
            $browsers = $this->getBrowserData();
            $operatingSystems = $this->getOSData();
            $stats = $this->getTotalStats();
            $visitorsData = $this->getVisitorsData();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $pageViews = [];
            $browsers = [];
            $operatingSystems = [];
            $stats = ['totalVisitors' => 0, 'totalPageViews' => 0, 'avgPageViews' => 0];
            $visitorsData = ['labels' => [], 'visitors' => [], 'pageViews' => []];
        }

        return view('livewire.admin.analytics-dashboard', [
            'stats' => $stats,
            'visitorsData' => $visitorsData,
            'pageViews' => $pageViews,
            'maxPageViews' => count($pageViews) > 0 ? max(array_column($pageViews, 'views')) : 1,
            'browsers' => $browsers,
            'maxBrowserUsers' => count($browsers) > 0 ? max(array_column($browsers, 'users')) : 1,
            'operatingSystems' => $operatingSystems,
            'maxOSUsers' => count($operatingSystems) > 0 ? max(array_column($operatingSystems, 'users')) : 1,
            'dateRangeOptions' => [
                '7days' => 'Last 7 Days',
                '30days' => 'Last 30 Days',
                '90days' => 'Last 90 Days',
            ],
        ]);
    }
}

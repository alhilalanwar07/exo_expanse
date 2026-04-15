<?php

namespace App\Livewire\Admin;

use App\Services\GoogleAnalyticsService;
use Carbon\Carbon;
use Livewire\Component;

class AnalyticsDashboard extends Component
{
    public string $dateRange = '7days';

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
        try {
            $dateRange = $this->getDateRange();
            $data = GoogleAnalyticsService::getVisitors($dateRange['startDate'], $dateRange['endDate']);

            return [
                'labels' => array_map(fn ($item) => Carbon::parse($item['date'])->format('M d'), $data),
                'visitors' => array_map(fn ($item) => $item['visitors'] ?? 0, $data),
                'pageViews' => array_map(fn ($item) => $item['pageViews'] ?? 0, $data),
            ];
        } catch (\Exception $e) {
            return [
                'labels' => [],
                'visitors' => [],
                'pageViews' => [],
            ];
        }
    }

    public function getPageViewsData(): array
    {
        try {
            $dateRange = $this->getDateRange();
            $data = GoogleAnalyticsService::getPageViews($dateRange['startDate'], $dateRange['endDate']);

            return array_slice(array_map(fn ($item) => [
                'url' => $item['url'] ?? 'N/A',
                'views' => $item['pageViews'] ?? 0,
            ], $data), 0, 10);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getBrowserData(): array
    {
        try {
            $dateRange = $this->getDateRange();
            $data = GoogleAnalyticsService::getBrowserData($dateRange['startDate'], $dateRange['endDate']);

            return array_slice(array_map(fn ($item) => [
                'name' => $item['name'] ?? 'Unknown',
                'users' => $item['users'] ?? 0,
            ], $data), 0, 8);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getOSData(): array
    {
        try {
            $dateRange = $this->getDateRange();
            $data = GoogleAnalyticsService::getOSData($dateRange['startDate'], $dateRange['endDate']);

            return array_slice(array_map(fn ($item) => [
                'name' => $item['name'] ?? 'Unknown',
                'users' => $item['users'] ?? 0,
            ], $data), 0, 8);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTotalStats(): array
    {
        try {
            $dateRange = $this->getDateRange();

            return [
                'totalVisitors' => GoogleAnalyticsService::getTotalUsers($dateRange['startDate'], $dateRange['endDate']),
                'totalPageViews' => GoogleAnalyticsService::getTotalPageViews($dateRange['startDate'], $dateRange['endDate']),
                'avgPageViews' => round(
                    GoogleAnalyticsService::getTotalPageViews($dateRange['startDate'], $dateRange['endDate']) /
                    max(GoogleAnalyticsService::getTotalUsers($dateRange['startDate'], $dateRange['endDate']), 1),
                    2
                ),
            ];
        } catch (\Exception $e) {
            return [
                'totalVisitors' => 0,
                'totalPageViews' => 0,
                'avgPageViews' => 0,
            ];
        }
    }

    public function render()
    {
        $pageViews = $this->getPageViewsData();
        $browsers = $this->getBrowserData();
        $operatingSystems = $this->getOSData();

        return view('livewire.admin.analytics-dashboard', [
            'stats' => $this->getTotalStats(),
            'visitorsData' => $this->getVisitorsData(),
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

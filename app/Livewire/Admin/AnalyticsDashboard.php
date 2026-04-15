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
            $period = $this->getPeriod();
            $data = GoogleAnalyticsService::getVisitors($period);

            return [
                'labels' => array_map(fn ($item) => $item['date']?->format('M d') ?? 'N/A', $data),
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
            $period = $this->getPeriod();
            $data = GoogleAnalyticsService::getPageViews($period);

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
            $period = $this->getPeriod();
            $data = GoogleAnalyticsService::getBrowserData($period);

            return array_slice(array_map(fn ($item) => [
                'name' => $item['browser'] ?? 'Unknown',
                'users' => $item['users'] ?? 0,
            ], $data), 0, 8);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getOSData(): array
    {
        try {
            $period = $this->getPeriod();
            $data = GoogleAnalyticsService::getOSData($period);

            return array_slice(array_map(fn ($item) => [
                'name' => $item['operatingSystem'] ?? 'Unknown',
                'users' => $item['users'] ?? 0,
            ], $data), 0, 8);
        } catch (\Exception $e) {
            return [];
        }
    }

    public function getTotalStats(): array
    {
        try {
            $period = $this->getPeriod();

            return [
                'totalVisitors' => GoogleAnalyticsService::getTotalUsers($period),
                'totalPageViews' => GoogleAnalyticsService::getTotalPageViews($period),
                'avgPageViews' => round(
                    GoogleAnalyticsService::getTotalPageViews($period) /
                    max(GoogleAnalyticsService::getTotalUsers($period), 1),
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
        return view('livewire.admin.analytics-dashboard', [
            'stats' => $this->getTotalStats(),
            'visitorsData' => $this->getVisitorsData(),
            'pageViews' => $this->getPageViewsData(),
            'browsers' => $this->getBrowserData(),
            'operatingSystems' => $this->getOSData(),
            'dateRangeOptions' => $this->dateRangeOptions,
        ]);
    }
}

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
            'title' => $item['title'] ?? 'N/A',
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

    public function getCountriesData(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getCountriesData($dateRange['startDate'], $dateRange['endDate']);

        return array_slice($data, 0, 8);
    }

    public function getTrafficSources(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getTrafficSources($dateRange['startDate'], $dateRange['endDate']);

        return array_slice($data, 0, 8);
    }

    public function getDeviceCategory(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getDeviceCategory($dateRange['startDate'], $dateRange['endDate']);

        return array_slice($data, 0, 5);
    }

    public function getCitiesData(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getCitiesData($dateRange['startDate'], $dateRange['endDate']);

        return array_slice($data, 0, 8);
    }

    public function getTopEvents(): array
    {
        $dateRange = $this->getDateRange();
        $data = GoogleAnalyticsService::getTopEvents($dateRange['startDate'], $dateRange['endDate']);

        // Filter out basic GA4 core events to focus on engagement, omit page_view or session_start if desired, 
        // but let's just return all top 8 for now.
        return array_slice($data, 0, 8);
    }

    public function getTotalStats(): array
    {
        $dateRange = $this->getDateRange();
        $totalUsers = GoogleAnalyticsService::getTotalUsers($dateRange['startDate'], $dateRange['endDate']);
        $totalViews = GoogleAnalyticsService::getTotalPageViews($dateRange['startDate'], $dateRange['endDate']);
        $avgSessionSeconds = GoogleAnalyticsService::getAverageSessionDuration($dateRange['startDate'], $dateRange['endDate']);
        
        // Format to mm:ss
        $minutes = floor($avgSessionSeconds / 60);
        $seconds = round($avgSessionSeconds % 60);
        $formattedDuration = sprintf('%02d:%02d', $minutes, $seconds);

        return [
            'totalVisitors' => $totalUsers,
            'totalPageViews' => $totalViews,
            'avgPageViews' => round($totalViews / max($totalUsers, 1), 2),
            'avgDuration' => $formattedDuration,
        ];
    }

    public function render()
    {
        $this->errorMessage = null;

        try {
            $pageViews = $this->getPageViewsData();
            $browsers = $this->getBrowserData();
            $operatingSystems = $this->getOSData();
            $countries = $this->getCountriesData();
            $trafficSources = $this->getTrafficSources();
            
            $cities = $this->getCitiesData();
            $devices = $this->getDeviceCategory();
            $events = $this->getTopEvents();
            
            $stats = $this->getTotalStats();
            $visitorsData = $this->getVisitorsData();
        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
            $pageViews = [];
            $browsers = [];
            $operatingSystems = [];
            $countries = [];
            $trafficSources = [];
            $cities = [];
            $devices = [];
            $events = [];
            $stats = ['totalVisitors' => 0, 'totalPageViews' => 0, 'avgPageViews' => 0, 'avgDuration' => '00:00'];
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
            'countries' => $countries,
            'maxCountryUsers' => count($countries) > 0 ? max(array_column($countries, 'users')) : 1,
            'trafficSources' => $trafficSources,
            'maxTrafficUsers' => count($trafficSources) > 0 ? max(array_column($trafficSources, 'users')) : 1,
            'cities' => $cities,
            'maxCityUsers' => count($cities) > 0 ? max(array_column($cities, 'users')) : 1,
            'devices' => $devices,
            'maxDeviceUsers' => count($devices) > 0 ? max(array_column($devices, 'users')) : 1,
            'events' => $events,
            'maxEventCount' => count($events) > 0 ? max(array_column($events, 'count')) : 1,
            'dateRangeOptions' => [
                '7days' => 'Last 7 Days',
                '30days' => 'Last 30 Days',
                '90days' => 'Last 90 Days',
                '180days' => 'Last 6 Months',
                '365days' => 'Last 1 Year',
            ],
        ]);
    }
}

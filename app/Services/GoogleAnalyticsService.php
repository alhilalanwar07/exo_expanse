<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Spatie\Analytics\Facades\Analytics;
use Spatie\Analytics\Period;

class GoogleAnalyticsService
{
    /**
     * Get GA4 property ID from environment
     */
    public static function getPropertyId(): ?string
    {
        return env('GOOGLE_ANALYTICS_PROPERTY_ID') ?? env('ANALYTICS_PROPERTY_ID');
    }

    /**
     * Check if credentials exist in config
     */
    private static function checkConfig(): bool
    {
        $propertyId = config('analytics.property_id');
        $credentials = config('analytics.service_account_credentials_json');

        if (! $propertyId || ! $credentials) {
            Log::warning('GA4 property ID or credentials not configured for Spatie Analytics');

            return false;
        }

        return true;
    }

    /**
     * Get visitors data for date range
     */
    public static function getVisitors(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        if (! self::checkConfig()) {
            return [];
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            $data = Analytics::fetchTotalVisitorsAndPageViews($period, 365);

            $results = $data->map(function ($item) {
                return [
                    'date' => $item['date']->format('Ymd'),
                    'visitors' => $item['activeUsers'] ?? 0,
                    'pageViews' => $item['screenPageViews'] ?? 0,
                ];
            })->toArray();

            // Sort by date
            usort($results, function ($a, $b) {
                return strcmp($a['date'] ?? '', $b['date'] ?? '');
            });

            return $results;
        } catch (\Exception $e) {
            Log::error('GA4 Visitors Fetch Error: '.$e->getMessage());
            throw self::formatError($e);
        }
    }

    /**
     * Get page views data
     */
    public static function getPageViews(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        if (! self::checkConfig()) {
            return [];
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            $data = Analytics::fetchMostVisitedPages($period, 10);

            return $data->map(function ($item) {
                return [
                    'url' => $item['pageTitle'] ?? 'N/A', // Spatie uses pageTitle
                    'pageViews' => $item['screenPageViews'] ?? 0,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('GA4 Page Views Fetch Error: '.$e->getMessage());
            throw self::formatError($e);
        }
    }

    /**
     * Get browser data
     */
    public static function getBrowserData(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        if (! self::checkConfig()) {
            return [];
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            // Spatie metrics: screenPageViews with dimension: browser
            $data = Analytics::get($period, ['activeUsers'], ['browser'], 10);

            return $data->map(function ($item) {
                return [
                    'name' => $item['browser'] ?? 'Unknown',
                    'users' => $item['activeUsers'] ?? 0,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('GA4 Browser Data Error: '.$e->getMessage());
            throw self::formatError($e);
        }
    }

    /**
     * Get operating system data
     */
    public static function getOSData(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        if (! self::checkConfig()) {
            return [];
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            // Spatie metrics: screenPageViews with dimension: operatingSystem
            $data = Analytics::get($period, ['activeUsers'], ['operatingSystem'], 10);

            return $data->map(function ($item) {
                return [
                    'name' => $item['operatingSystem'] ?? 'Unknown',
                    'users' => $item['activeUsers'] ?? 0,
                ];
            })->toArray();
        } catch (\Exception $e) {
            Log::error('GA4 OS Data Error: '.$e->getMessage());
            throw self::formatError($e);
        }
    }

    /**
     * Get total users
     */
    public static function getTotalUsers(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        if (! self::checkConfig()) {
            return 0;
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            $data = Analytics::get($period, ['activeUsers']);

            return $data->sum('activeUsers') ?? 0;
        } catch (\Exception $e) {
            Log::error('GA4 Total Users Error: '.$e->getMessage());

            return 0; // Return 0 to prevent entire dashboard crashing on sum
        }
    }

    /**
     * Get total page views
     */
    public static function getTotalPageViews(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        if (! self::checkConfig()) {
            return 0;
        }

        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();
            $period = Period::create($startDate, $endDate);

            $data = Analytics::get($period, ['screenPageViews']);

            return $data->sum('screenPageViews') ?? 0;
        } catch (\Exception $e) {
            Log::error('GA4 Total Page Views Error: '.$e->getMessage());

            return 0; // Return 0 to prevent entire dashboard crashing on sum
        }
    }

    /**
     * Clean up temp credentials file (No longer needed, kept for compatibility)
     */
    public static function cleanup(): void
    {
        // Spatie handles credentials internally via array config
    }

    /**
     * Formats exception errors generated by Google API
     */
    private static function formatError(\Exception $e): \Exception
    {
        $errorJson = json_decode($e->getMessage(), true);
        $message = is_array($errorJson) && isset($errorJson['message'])
            ? $errorJson['message']
            : $e->getMessage();

        return new \Exception($message);
    }
}

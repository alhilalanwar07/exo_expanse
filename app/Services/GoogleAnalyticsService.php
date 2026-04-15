<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Spatie\LaravelAnalytics\Facades\LaravelAnalytics;
use Spatie\LaravelAnalytics\Period;

class GoogleAnalyticsService
{
    /**
     * Get decoded credentials from base64 in .env
     */
    public static function getDecodedCredentials(): array
    {
        try {
            $base64Credentials = config('services.google.analytics_credentials_base64');

            if (! $base64Credentials) {
                throw new \Exception('GA4 credentials not found in .env');
            }

            $jsonCredentials = base64_decode($base64Credentials, true);

            if (! $jsonCredentials) {
                throw new \Exception('Failed to decode base64 credentials');
            }

            $credentials = json_decode($jsonCredentials, true);

            if (! $credentials) {
                throw new \Exception('Invalid JSON in decoded credentials');
            }

            return $credentials;
        } catch (\Exception $e) {
            Log::error('GA4 Credentials Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Setup Google Analytics client
     */
    public static function setupAnalytics(): void
    {
        try {
            $credentials = static::getDecodedCredentials();

            if (empty($credentials)) {
                throw new \Exception('No valid credentials');
            }

            // Create temp file for credentials
            $tempFile = storage_path('app/ga4-credentials-temp.json');
            file_put_contents($tempFile, json_encode($credentials));

            // Setup Laravel Analytics
            config([
                'analytics.credentials_path' => $tempFile,
                'analytics.property_id' => env('GOOGLE_ANALYTICS_PROPERTY_ID'),
            ]);
        } catch (\Exception $e) {
            Log::error('GA4 Setup Error: '.$e->getMessage());
        }
    }

    /**
     * Get visitors data for date range
     */
    public static function getVisitors(Period $period): array
    {
        try {
            static::setupAnalytics();

            return LaravelAnalytics::fetchVisitorsAndPageViews($period);
        } catch (\Exception $e) {
            Log::error('GA4 Visitors Fetch Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get page views data
     */
    public static function getPageViews(Period $period): array
    {
        try {
            static::setupAnalytics();

            return LaravelAnalytics::fetchMostVisitedPages($period);
        } catch (\Exception $e) {
            Log::error('GA4 Page Views Fetch Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get browser data
     */
    public static function getBrowserData(Period $period): array
    {
        try {
            static::setupAnalytics();

            return LaravelAnalytics::fetchTopBrowsers($period);
        } catch (\Exception $e) {
            Log::error('GA4 Browser Data Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get operating system data
     */
    public static function getOSData(Period $period): array
    {
        try {
            static::setupAnalytics();

            return LaravelAnalytics::fetchTopOperatingSystems($period);
        } catch (\Exception $e) {
            Log::error('GA4 OS Data Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get device category data
     */
    public static function getDeviceData(Period $period): array
    {
        try {
            static::setupAnalytics();

            return LaravelAnalytics::fetchMostVisitedPages($period, 'deviceCategory');
        } catch (\Exception $e) {
            Log::error('GA4 Device Data Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get total users
     */
    public static function getTotalUsers(Period $period): int
    {
        try {
            static::setupAnalytics();
            $data = LaravelAnalytics::fetchVisitorsAndPageViews($period);

            return array_sum(array_column($data, 'visitors'));
        } catch (\Exception $e) {
            Log::error('GA4 Total Users Error: '.$e->getMessage());

            return 0;
        }
    }

    /**
     * Get total page views
     */
    public static function getTotalPageViews(Period $period): int
    {
        try {
            static::setupAnalytics();
            $data = LaravelAnalytics::fetchVisitorsAndPageViews($period);

            return array_sum(array_column($data, 'pageViews'));
        } catch (\Exception $e) {
            Log::error('GA4 Total Page Views Error: '.$e->getMessage());

            return 0;
        }
    }

    /**
     * Clean up temp credentials file
     */
    public static function cleanup(): void
    {
        $tempFile = storage_path('app/ga4-credentials-temp.json');
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
    }
}

<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
     * Get visitors data for date range
     */
    public static function getVisitors(Carbon $startDate = null, Carbon $endDate = null): array
    {
        try {
            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();

            // Mock data for demo - replace with actual API call
            $days = $startDate->diffInDays($endDate);
            $data = [];

            for ($i = 0; $i <= $days; $i++) {
                $date = $startDate->clone()->addDays($i);
                $data[] = [
                    'date' => $date->format('Y-m-d'),
                    'visitors' => rand(50, 200),
                    'pageViews' => rand(100, 500),
                ];
            }

            return $data;
        } catch (\Exception $e) {
            Log::error('GA4 Visitors Fetch Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get page views data
     */
    public static function getPageViews(Carbon $startDate = null, Carbon $endDate = null): array
    {
        try {
            // Mock data for demo
            return [
                ['url' => '/form-sosmed', 'pageViews' => 156],
                ['url' => '/admin/dashboard', 'pageViews' => 143],
                ['url' => '/blog', 'pageViews' => 98],
                ['url' => '/admin/siswakkri/history', 'pageViews' => 87],
                ['url' => '/', 'pageViews' => 76],
                ['url' => '/admin/invitations', 'pageViews' => 65],
                ['url' => '/admin/users', 'pageViews' => 54],
                ['url' => '/blog/example-post', 'pageViews' => 43],
                ['url' => '/admin/themes', 'pageViews' => 32],
                ['url' => '/admin/settings', 'pageViews' => 21],
            ];
        } catch (\Exception $e) {
            Log::error('GA4 Page Views Fetch Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get browser data
     */
    public static function getBrowserData(Carbon $startDate = null, Carbon $endDate = null): array
    {
        try {
            // Mock data for demo
            return [
                ['name' => 'Chrome', 'users' => 450],
                ['name' => 'Firefox', 'users' => 180],
                ['name' => 'Safari', 'users' => 210],
                ['name' => 'Edge', 'users' => 95],
                ['name' => 'Opera', 'users' => 45],
                ['name' => 'Samsung Internet', 'users' => 35],
                ['name' => 'UC Browser', 'users' => 20],
                ['name' => 'Other', 'users' => 15],
            ];
        } catch (\Exception $e) {
            Log::error('GA4 Browser Data Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get operating system data
     */
    public static function getOSData(Carbon $startDate = null, Carbon $endDate = null): array
    {
        try {
            // Mock data for demo
            return [
                ['name' => 'Windows', 'users' => 520],
                ['name' => 'Android', 'users' => 380],
                ['name' => 'macOS', 'users' => 210],
                ['name' => 'iOS', 'users' => 195],
                ['name' => 'Linux', 'users' => 85],
                ['name' => 'Chrome OS', 'users' => 35],
                ['name' => 'Other', 'users' => 25],
            ];
        } catch (\Exception $e) {
            Log::error('GA4 OS Data Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get total users
     */
    public static function getTotalUsers(Carbon $startDate = null, Carbon $endDate = null): int
    {
        try {
            $visitors = self::getVisitors($startDate, $endDate);

            return array_sum(array_column($visitors, 'visitors'));
        } catch (\Exception $e) {
            Log::error('GA4 Total Users Error: '.$e->getMessage());

            return 0;
        }
    }

    /**
     * Get total page views
     */
    public static function getTotalPageViews(Carbon $startDate = null, Carbon $endDate = null): int
    {
        try {
            $visitors = self::getVisitors($startDate, $endDate);

            return array_sum(array_column($visitors, 'pageViews'));
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

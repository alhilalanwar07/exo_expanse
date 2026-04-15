<?php

namespace App\Services;

use Carbon\Carbon;
use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Google\Analytics\Data\V1beta\RunReportRequest;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    private static ?BetaAnalyticsDataClient $client = null;

    /**
     * Get decoded credentials from base64 in .env
     */
    public static function getDecodedCredentials(): array
    {
        try {
            $base64Credentials = config('services.google.analytics_credentials_base64');

            if (! $base64Credentials) {
                Log::warning('GA4 credentials not found in .env');

                return [];
            }

            $jsonCredentials = base64_decode($base64Credentials, true);

            if (! $jsonCredentials) {
                Log::warning('Failed to decode base64 credentials');

                return [];
            }

            $credentials = json_decode($jsonCredentials, true);

            if (! $credentials) {
                Log::warning('Invalid JSON in decoded credentials');

                return [];
            }

            return $credentials;
        } catch (\Exception $e) {
            Log::error('GA4 Credentials Error: '.$e->getMessage());

            return [];
        }
    }

    /**
     * Get GA4 property ID from credentials
     */
    public static function getPropertyId(): ?string
    {
        try {
            $credentials = static::getDecodedCredentials();

            if (empty($credentials) || ! isset($credentials['project_id'])) {
                return null;
            }

            // Get property ID from environment or use default from credentials
            $propertyId = env('GOOGLE_ANALYTICS_PROPERTY_ID');

            return $propertyId ? 'properties/'.$propertyId : null;
        } catch (\Exception $e) {
            Log::error('GA4 Property ID Error: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Setup Google Analytics client
     */
    public static function setupClient(): bool
    {
        try {
            if (self::$client !== null) {
                return true;
            }

            $credentials = static::getDecodedCredentials();

            if (empty($credentials)) {
                Log::warning('GA4 credentials not available');

                return false;
            }

            // Create temp file for credentials
            $tempFile = storage_path('app/ga4-credentials-temp.json');
            if (! file_put_contents($tempFile, json_encode($credentials))) {
                Log::warning('Failed to write credentials temp file');

                return false;
            }

            // Initialize client with credentials
            self::$client = new BetaAnalyticsDataClient([
                'credentials' => $tempFile,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('GA4 Client Setup Error: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Execute analytics query
     */
    private static function executeQuery(
        array $dimensions = [],
        array $metrics = [],
        ?Carbon $startDate = null,
        ?Carbon $endDate = null,
        ?int $limit = null
    ): array {
        try {
            if (! static::setupClient() || ! self::$client) {
                return [];
            }

            $propertyId = static::getPropertyId();
            if (! $propertyId) {
                Log::warning('GA4 property ID not configured');

                return [];
            }

            $startDate = $startDate ?? Carbon::now()->subDays(7);
            $endDate = $endDate ?? Carbon::now();

            $dimensionObjects = array_map(
                fn ($dim) => new Dimension(['name' => $dim]),
                $dimensions
            );

            $metricObjects = array_map(
                fn ($metric) => new Metric(['name' => $metric]),
                $metrics
            );

            $request = new RunReportRequest([
                'property' => $propertyId,
                'dimensions' => $dimensionObjects,
                'metrics' => $metricObjects,
                'date_ranges' => [
                    new DateRange([
                        'start_date' => $startDate->format('Y-m-d'),
                        'end_date' => $endDate->format('Y-m-d'),
                    ]),
                ],
                'limit' => $limit ?? 1000,
            ]);

            $response = self::$client->runReport($request);

            $results = [];
            foreach ($response->getRows() as $row) {
                $rowData = [];
                foreach ($row->getDimensionValues() as $i => $value) {
                    $rowData[$dimensions[$i] ?? 'dimension_'.$i] = $value->getValue();
                }
                foreach ($row->getMetricValues() as $i => $value) {
                    $rowData[$metrics[$i] ?? 'metric_'.$i] = (int) $value->getValue();
                }
                $results[] = $rowData;
            }

            return $results;
        } catch (\Exception $e) {
            Log::error('GA4 Query Error: '.$e->getMessage());

            // Extract the readable message from Google API JSON error
            $errorJson = json_decode($e->getMessage(), true);
            $message = is_array($errorJson) && isset($errorJson['message'])
                ? $errorJson['message']
                : $e->getMessage();

            throw new \Exception($message);
        }
    }

    /**
     * Get visitors data for date range
     */
    public static function getVisitors(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $data = static::executeQuery(
            ['date'],
            ['activeUsers', 'screenPageViews'],
            $startDate,
            $endDate,
            365
        );

        // Sort by date
        usort($data, function ($a, $b) {
            return strcmp($a['date'] ?? '', $b['date'] ?? '');
        });

        return array_map(fn ($item) => [
            'date' => $item['date'] ?? null,
            'visitors' => $item['activeUsers'] ?? 0,
            'pageViews' => $item['screenPageViews'] ?? 0,
        ], $data);
    }

    /**
     * Get page views data
     */
    public static function getPageViews(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $data = static::executeQuery(
            ['pagePath'],
            ['screenPageViews'],
            $startDate,
            $endDate,
            10
        );

        return array_map(fn ($item) => [
            'url' => $item['pagePath'] ?? 'N/A',
            'pageViews' => $item['screenPageViews'] ?? 0,
        ], $data);
    }

    /**
     * Get browser data
     */
    public static function getBrowserData(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $data = static::executeQuery(
            ['browserName'],
            ['activeUsers'],
            $startDate,
            $endDate,
            10
        );

        return array_map(fn ($item) => [
            'name' => $item['browserName'] ?? 'Unknown',
            'users' => $item['activeUsers'] ?? 0,
        ], $data);
    }

    /**
     * Get operating system data
     */
    public static function getOSData(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $data = static::executeQuery(
            ['operatingSystem'],
            ['activeUsers'],
            $startDate,
            $endDate,
            10
        );

        return array_map(fn ($item) => [
            'name' => $item['operatingSystem'] ?? 'Unknown',
            'users' => $item['activeUsers'] ?? 0,
        ], $data);
    }

    /**
     * Get total users
     */
    public static function getTotalUsers(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        $data = static::executeQuery([], ['activeUsers'], $startDate, $endDate);

        return array_sum(array_column($data, 'activeUsers'));
    }

    /**
     * Get total page views
     */
    public static function getTotalPageViews(?Carbon $startDate = null, ?Carbon $endDate = null): int
    {
        $data = static::executeQuery([], ['screenPageViews'], $startDate, $endDate);

        return array_sum(array_column($data, 'screenPageViews'));
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

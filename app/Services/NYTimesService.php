<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NYTimesService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.nytimes.base_url');
        $this->apiKey = config('services.nytimes.api_key'); // Ensure you have these in your config/services.php
    }

    /**
     * Fetch best-sellers history from NYTimes API.
     *
     * @param array $params
     * @return array|null
     */
    public function fetchBestSellersHistory(array $params): ?array
    {
        try {
            $queryParams = array_merge($params, ['api-key' => $this->apiKey]);

            $response = Http::baseUrl($this->baseUrl)
                ->get('/lists/best-sellers/history.json', $queryParams);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('NYTimes API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('NYTimes API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}
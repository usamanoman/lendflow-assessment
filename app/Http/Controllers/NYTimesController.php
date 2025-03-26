<?php

namespace App\Http\Controllers;

use App\Http\Requests\NYTimesBestSellersRequest;
use App\Services\NYTimesService;

class NYTimesController extends Controller
{
    /**
     * Fetch the NY Best Seller history from the NYTimes API.
    *
    * @param NYTimesBestSellersRequest $request
    * @param NYTimesService $nyTimesService
    * @return \Illuminate\Http\JsonResponse
    */
    public function getBestSellersHistory(NYTimesBestSellersRequest $request, NYTimesService $nyTimesService)
    {
        try {
            $data = $nyTimesService->fetchBestSellersHistory($request->validated());
            if ($data && $data['status'] === 'OK') {
                    return $data;
            }

            return response()->json([
                    'error' => 'Failed to fetch data from NYTimes API',
                    'details' => $data['details'] ?? 'No additional details available',
            ], $data['status'] ?? 500);
        } catch (\Exception $e) {
            return response()->json([
                    'error' => 'An unexpected error occurred while fetching data from NYTimes API',
                    'message' => $e->getMessage(),
            ], 500);
        }
    }
}
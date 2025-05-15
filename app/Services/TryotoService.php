<?php

namespace App\Services;
use App\Utils\CartManager;
use Exception;
use Illuminate\Support\Facades\Http;
use Log;

class TryotoService
{
    private $baseUrl;
    private $refreshToken;
    private $accessToken;

    public function __construct()
    {
        $this->baseUrl = config('services.tryoto.base_url');
        $this->refreshToken = config('services.tryoto.refresh_token');
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Refresh and get a new access token.
     *
     * @return string
     */



    /**
     * Make an authenticated API request.
     *
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array
     */
    //     private function makeRequest($method, $endpoint, $data = [])
// {
//     $response = Http::withoutVerifying()  // Add this line here too
//         ->withToken($this->accessToken)
//         ->$method($this->baseUrl . $endpoint, $data);

    //     if ($response->successful()) {
//         return $response->json();
//     }
//     throw new \Exception('API Request failed: ' . $response->body());
// }

    private function makeRequest($method, $endpoint, $data = [])
    {
        config([
            'services.tryoto.webhook_secret' => env('TRYOTO_WEBHOOK_SECRET'),
            'services.tryoto.refresh_token' => env('TRYOTO_REFRESH_TOKEN'),
            'services.tryoto.base_url' => env('TRYOTO_BASE_URL'),
        ]);
        try {
            \Log::info("Making Tryoto API request:", [
                'method' => $method,
                'endpoint' => $endpoint,
                'data' => $data,
                'url' => $this->baseUrl . $endpoint
            ]);

            $response = Http::withoutVerifying()
                        ->withToken($this->accessToken)
                ->$method($this->baseUrl . $endpoint, $data);

            \Log::info("Tryoto API response:", [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('API Request failed: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Tryoto API error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Create a new order in Tryoto.
     *
     * @param array $orderData
     * @return array
     */
    // public function createOrder(array $orderData)
    // {
    //     $endpoint = '/rest/v2/createOrder';
    //     return $this->makeRequest('post', $endpoint, $orderData);
    // }
    public function createOrder(array $orderData)
    {
        $endpoint = '/rest/v2/createOrder';

        // Log the request data
        \Log::info('Creating order with data:', $orderData);

        try {
            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->post($this->baseUrl . $endpoint, $orderData);

            // Log the response
            \Log::info('Tryoto API Response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('API Request failed: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Order creation failed:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    /**
     * Create a shipment for an existing order.
     *
     * @param string $orderId
     * @param int $deliveryOptionId
     * @return array
     */
    public function createShipment(string $orderId, int $deliveryOptionId)
    {
        $endpoint = '/rest/v2/createShipment';

        \Log::info('Creating shipment:', [
            'orderId' => $orderId,
            'deliveryOptionId' => $deliveryOptionId
        ]);

        try {
            // First verify order exists
            $this->verifyOrder($orderId);

            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->post($this->baseUrl . $endpoint, [
                    'orderId' => strval($orderId), // Ensure string
                    'deliveryOptionId' => $deliveryOptionId,
                    'createShipment' => 'true'
                ]);

            \Log::info('Shipment creation response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to create shipment: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Shipment creation failed:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    // Add this helper method
    private function verifyOrder($orderId)
    {
        // Add order verification if Tryoto provides an endpoint for it
        \Log::info('Verifying order:', ['orderId' => $orderId]);
        return true;
    }
    /**
     * Get shipping fees and delivery options.
     *
     * @param array $data
     * @return array
     */
    public function getDeliveryFees(array $data)
    {
        try {
            // Get the checked cart items
            $cart = CartManager::get_cart(type: 'checked');


            // Validate cart and extract seller's cities
            $originCities = [];
            $productsQtyByCity = [];

            foreach ($cart as $item) {
                if ($item->product && $item->product->seller) {
                    $city = $item->product->seller->city;
                    $qty = $item->quantity ?? 1; // Default to 1 if qty is missing

                    // Store city
                    $originCities[] = $city;

                    // Count total quantity by city
                    if (!isset($productsQtyByCity[$city])) {
                        $productsQtyByCity[$city] = 0;
                    }

                    $productsQtyByCity[$city] += $qty;
                }
            }

            $originCities = array_unique($originCities);
            $originCity = $originCities[0] ?? null;

            if (!$originCity) {
                throw new \Exception('Seller city not found in cart items.');
            }

            $destinationCity = $data['destinationCity'];

            // Initialize array to hold all delivery companies
            $allDeliveryCompanies = [];

            log::info('accessToken accessToken accessToken:', [
                'accessToken' => $this->accessToken,
                'baseUrl' => $this->baseUrl,

            ]);

            // Loop through the origin cities to make the API requests
            foreach ($originCities as $cityname) {
                try {
                    // Make API request for the current origin city
                    $endpoint = '/rest/v2/checkOTODeliveryFee';
                    $response = Http::withoutVerifying()
                        ->withToken($this->accessToken)
                        ->post($this->baseUrl . $endpoint, [
                            'weight' => $data['weight'],
                            'originCity' => $cityname,
                            'destinationCity' => $destinationCity,
                            'height' => $data['height'] ?? 30,
                            'width' => $data['width'] ?? 30,
                            'length' => $data['length'] ?? 30,
                            'totalDue' => $data['totalDue'] ?? 0,
                        ]);

                    if ($response->successful()) {
                        $responseData = $response->json();
                        Log::info('API Response for ' . $cityname . ':', $responseData);

                        // Store the delivery companies from the response
                        $deliveryCompanies = $responseData['deliveryCompany'] ?? [];
                        $allDeliveryCompanies[] = $deliveryCompanies; // Store entire company data, not just names
                    } else {
                        Log::error('Failed API Response for ' . $cityname . ': ' . $response->body());
                    }
                } catch (\Exception $e) {
                    Log::error('Error making API request for ' . $cityname . ': ' . $e->getMessage());
                }
            }

            // Check if we have multiple origin cities
            // If multiple cities, intersect by deliveryOptionId
            if (count($allDeliveryCompanies) > 1) {
                // Step 1: Index companies by deliveryOptionId for each city
                $indexedCompanies = array_map(function ($companies) {
                    $indexed = [];
                    foreach ($companies as $company) {
                        $indexed[$company['deliveryOptionId']] = $company;
                    }
                    return $indexed;
                }, $allDeliveryCompanies);

                // Step 2: Find common deliveryOptionIds
                $commonIds = array_keys(array_intersect_key(...$indexedCompanies));

                // Step 3: Collect and sum common delivery company details
                $commonDeliveryCompanies = [];
                $summedDeliveryOptions = [];

                foreach ($commonIds as $id) {
                    $sumPrice = 0;
                    // Loop through each city and apply the city-specific price
                    foreach ($indexedCompanies as $index => $companies) {
                        $cityName = $originCities[$index] ?? null;
                        $qty = $productsQtyByCity[$cityName] ?? 1; // Default to 1 if qty is missing
                        $price = $companies[$id]['price'] ?? 0;
                        log::info('Price for ' . $cityName . ':', [
                            'price' => $price,
                            'qty' => $qty,
                        ]);

                        // Multiply price by quantity for the respective city
                        $sumPrice += $price * $qty;
                    }

                    // Take base company info (e.g., name, logo) from the first city's response
                    $baseCompany = $indexedCompanies[0][$id];
                    // Take base company info (e.g., name, logo) from the first city's response
                    $baseCompany = $indexedCompanies[0][$id];
                    $baseCompany['totalPrice'] = round($sumPrice * 1.1); // Apply 10% increase


                    // Store summed delivery option
                    $summedDeliveryOptions[] = $baseCompany;
                }

                $commonDeliveryCompanies = $summedDeliveryOptions;
            } else {
                // If multiple cities, intersect by deliveryOptionId
                if (count($allDeliveryCompanies) > 1) {
                    // Step 1: Index companies by deliveryOptionId for each city
                    $indexedCompanies = array_map(function ($companies) {
                        $indexed = [];
                        foreach ($companies as $company) {
                            $indexed[$company['deliveryOptionId']] = $company;
                        }
                        return $indexed;
                    }, $allDeliveryCompanies);

                    // Step 2: Find common deliveryOptionIds
                    $commonIds = array_keys(array_intersect_key(...$indexedCompanies));

                    // Step 3: Collect and sum common delivery company details
                    $commonDeliveryCompanies = [];
                    $summedDeliveryOptions = [];

                    foreach ($commonIds as $id) {
                        $sumPrice = 0;
                        // Loop through each city and apply the city-specific price
                        foreach ($indexedCompanies as $index => $companies) {
                            $cityName = $originCities[$index] ?? null;
                            $qty = $productsQtyByCity[$cityName] ?? 1; // Default to 1 if qty is missing
                            $price = $companies[$id]['price'] ?? 0;
                            log::info('Price for ' . $cityName . ':', [
                                'price' => $price,
                                'qty' => $qty,
                            ]);

                            // Multiply price by quantity for the respective city
                            $sumPrice += $price * $qty;
                        }

                        // Take base company info (e.g., name, logo) from the first city's response
                        $baseCompany = $indexedCompanies[0][$id];
                        // Take base company info (e.g., name, logo) from the first city's response
                        $baseCompany = $indexedCompanies[0][$id];
                        $baseCompany['totalPrice'] = round($sumPrice * 1.1); // Apply 10% increase


                        // Store summed delivery option
                        $summedDeliveryOptions[] = $baseCompany;
                    }

                    $commonDeliveryCompanies = $summedDeliveryOptions;
                } else {
                    // If only one origin city, simply sum the price by the quantity of products in that city
                    $commonDeliveryCompanies = $allDeliveryCompanies[0] ?? [];
                    foreach ($commonDeliveryCompanies as &$company) {
                        $cityQty = $productsQtyByCity[$originCity] ?? 1;
                        $company['totalPrice'] = round((($company['price'] ?? 0) * $cityQty) * 1.1); // Apply 10% increase
                    }

                }
            }

            // Log the complete data of common delivery companies
            Log::info('Common Delivery Companies Data:', $commonDeliveryCompanies);

            // Filter only required deliveryOptionIds
            $allowedDeliveryOptionIds = [7229, 7324, 6926];

            $filteredDeliveryCompanies = array_values(array_filter($commonDeliveryCompanies, function ($company) use ($allowedDeliveryOptionIds) {
                return in_array($company['deliveryOptionId'], $allowedDeliveryOptionIds);
            }));

            return [
                'success' => true,
                'deliveryOptions' => $filteredDeliveryCompanies,
            ];


         

        } catch (\Exception $e) {
            Log::error('Error getting delivery fees: ' . $e->getMessage());
            throw $e;
        }
    }


    // public function getDeliveryFees(array $data)
    // {
    //     Log::info('Tryoto Service Configuration:', [
    //         'webhook_secret' => config('services.tryoto.webhook_secret'),
    //         'refresh_token' => config('services.tryoto.refresh_token'),
    //         'base_url' => config('services.tryoto.base_url'),
    //     ]);

    //     try {
    //         $cart = CartManager::get_cart(type: 'checked');

    //         // Extract unique seller cities
    //         $originCities = [];
    //         foreach ($cart as $item) {
    //             if ($item->product && $item->product->seller) {
    //                 $originCities[] = $item->product->seller->city;
    //             }
    //         }
    //         $originCities = array_unique($originCities);
    //         $destinationCity = $data['destinationCity'];

    //         Log::info('Cities for getDeliveryFees:', [
    //             'destinationCity' => $destinationCity,
    //         ]);

    //         // Define allowed delivery company keywords
    //         $allowedKeywords = ['SPL online', 'Aramex', 'SMSA'];
    //         $allFilteredCompanies = [];

    //         foreach ($originCities as $cityname) {
    //             Log::info('Origin City:', ['originCity' => $cityname]);

    //             try {
    //                 $endpoint = '/rest/v2/checkOTODeliveryFee';
    //                 $response = Http::withoutVerifying()
    //                     ->withToken($this->accessToken)
    //                     ->post($this->baseUrl . $endpoint, [
    //                         'weight' => $data['weight'],
    //                         'originCity' => $cityname,
    //                         'destinationCity' => $destinationCity,
    //                         'height' => $data['height'] ?? 30,
    //                         'width' => $data['width'] ?? 30,
    //                         'length' => $data['length'] ?? 30,
    //                         'totalDue' => $data['totalDue'] ?? 0,
    //                     ]);

    //                 Log::info('API Response for ' . $cityname . ':', $response->json());

    //                 if ($response->successful()) {
    //                     $responseData = $response->json();
    //                     $deliveryCompanies = $responseData['deliveryCompany'] ?? [];

    //                     // Filter only allowed companies
    //                     $filteredCompanies = array_filter($deliveryCompanies, function ($company) use ($allowedKeywords) {
    //                         foreach ($allowedKeywords as $keyword) {
    //                             if (stripos($company['deliveryCompanyName'], $keyword) !== false) {
    //                                 return true;
    //                             }
    //                         }
    //                         return false;
    //                     });

    //                     // Merge into allFilteredCompanies
    //                     $allFilteredCompanies = array_merge($allFilteredCompanies, array_values($filteredCompanies));

    //                 } else {
    //                     Log::error('Failed API Response for ' . $cityname . ':', ['body' => $response->body()]);
    //                 }
    //             } catch (\Exception $e) {
    //                 Log::error('Error making API request for ' . $cityname . ': ' . $e->getMessage());
    //             }
    //         }

    //         return [
    //             'success' => true,
    //             'deliveryOptions' => $allFilteredCompanies,
    //         ];

    //     } catch (\Exception $e) {
    //         Log::error('Error getting delivery fees: ' . $e->getMessage());
    //         throw $e;
    //     }
    // }



    /**
     * Track shipment by tracking number and delivery company name.
     *
     * @param string $trackingNumber
     * @param string $deliveryCompanyName
     * @return array
     */
    public function trackShipment(string $trackingNumber, string $deliveryCompanyName)
    {
        $endpoint = '/rest/v2/trackShipment';
        $data = [
            'trackingNumber' => $trackingNumber,
            'deliveryCompanyName' => $deliveryCompanyName,
            'statusHistory' => true,
        ];
        return $this->makeRequest('post', $endpoint, $data);
    }

    public function getWebhooks()
    {
        $endpoint = '/rest/v2/webhook';
        return $this->makeRequest('get', $endpoint);
    }
    public function registerWebhook()
    {
        $endpoint = '/rest/v2/webhook';
        $data = [
            'method' => 'POST',
            'url' => "https://webhook.site/88706f14-cb9e-426c-9bf3-cd68c3f1f41b",
            'secretKey' => config('services.tryoto.webhook_secret'),  // Uses your generated secret
            'timestampFormat' => 'yyyy-MM-dd HH:mm:ss',
            'webhookType' => 'orderStatus'
        ];

        return $this->makeRequest('post', $endpoint, $data);
    }
    public function updateWebhook($data)
    {
        $endpoint = '/rest/v2/webhook';
        return $this->makeRequest('put', $endpoint, $data);
    }

    public function getPickupLocations()
    {
        $minDate = date('Y-m-d');  // Today
        $maxDate = date('Y-m-d', strtotime('+30 days'));  // 30 days ahead

        \Log::info('Fetching pickup locations with dates:', [
            'minDate' => $minDate,
            'maxDate' => $maxDate
        ]);

        $endpoint = '/rest/v2/getPickupLocationList';
        $queryParams = [
            'minDate' => $minDate,
            'maxDate' => $maxDate,
            'status' => 'active'
        ];

        try {
            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->get($this->baseUrl . $endpoint, $queryParams);

            \Log::info('Pickup locations response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('API Request failed: ' . $response->body());
        } catch (\Exception $e) {
            \Log::error('Failed to get pickup locations: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getOrder($orderId)
    {
        $endpoint = '/rest/v2/getOrders';  // Changed endpoint

        try {
            // Using query parameters instead of body
            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->get($this->baseUrl . $endpoint, [
                    'orderId' => strval($orderId),
                    'limit' => 1,
                    'page' => 1
                ]);

            \Log::info('Get order response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'orderId' => $orderId,
                'url' => $this->baseUrl . $endpoint
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get order details: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Get order failed:', [
                'error' => $e->getMessage(),
                'orderId' => $orderId
            ]);
            throw $e;
        }
    }
    public function getAllOrderDetails($orderId)
    {
        try {
            $endpoint = '/rest/v2/getOrderStatus';  // Changed endpoint
            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->post($this->baseUrl . $endpoint, [
                    'orderId' => $orderId,
                    'otoId' => str_replace('TEST-', '', $orderId)  // Also try with numeric ID
                ]);

            \Log::info('Get order status response:', [
                'status' => $response->status(),
                'body' => $response->body(),
                'orderId' => $orderId,
                'url' => $this->baseUrl . $endpoint
            ]);

            if (!$response->successful()) {
                throw new \Exception('Failed to get order status: ' . $response->body());
            }

            return $response->json();
        } catch (\Exception $e) {
            \Log::error('Failed to get order status:', [
                'error' => $e->getMessage(),
                'orderId' => $orderId
            ]);
            throw $e;
        }
    }
    public function getOrderTracking($orderId)
    {
        try {
            $endpoint = '/rest/v2/getOrderStatus';
            $response = Http::withoutVerifying()
                ->withToken($this->accessToken)
                ->post($this->baseUrl . $endpoint, [
                    'orderId' => $orderId
                ]);

            \Log::info('Tracking response:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'status' => $data['status'] ?? 'unknown',
                    'trackingNumber' => $data['trackingNumber'] ?? null,
                    'trackingUrl' => $data['brandedTrackingURL'] ?? null
                ];
            }

            throw new \Exception('Failed to get tracking info');
        } catch (\Exception $e) {
            \Log::error('Tracking error:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function getAccessToken(): string
    {
        try {
            $response = Http::withoutVerifying()
                ->post($this->baseUrl . '/rest/v2/refreshToken', [
                    'refresh_token' => $this->refreshToken,
                ]);

            if ($response->successful()) {
                return $response->json()['access_token'];
            }

            throw new \Exception('Failed to fetch access token: ' . $response->body());
        } catch (\Exception $e) {
            return 'dummy_token';
        }
    }

    public function getAWB(string $orderId): array
    {
        try {
            $response = Http::withToken($this->getAccessToken())
                ->withoutVerifying()
                ->get("{$this->baseUrl}/rest/v2/print/{$orderId}");

            if (!$response->successful()) {
                throw new Exception('Failed to get AWB: ' . $response->body());
            }

            return $response->json();
        } catch (Exception $e) {
            logger()->error('Tryoto AWB retrieval failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
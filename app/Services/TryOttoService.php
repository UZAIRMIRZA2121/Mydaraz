<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TryOttoService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.tryoto.base_url');
    }

    public function getApiAccessToken()
    {
        $refreshToken = "AMf-vBxZSeMMlhGDWJTWYFbGs5CUJxnLS_0XUA2jviHNkhjFy23v9kVlNueM4FpawMKwrVfVOIaZlsx5Q_bh8X55lP6h6hwPsw_t2QQ2dgLP3SIcrD4gYeVjT9X4qQzfTCzzoTK1ZOU-WMAM2iX46fP8vcdWioU2p0wffaU6ALRPK-VXmXAKw1cbwkwAJfDxzW8rBOqn0S-TCUFLtLgLWZqlm0";
    
        try {
            // Ensure the base URL has a trailing slash
            $url = rtrim($this->baseUrl, '/') . "/refreshToken?refresh_token=$refreshToken";
    
            \Log::info("Requesting new access token from: $url");
    
            $response = Http::withHeaders([
                'Accept' => 'application/json',
            ])->get($url); // 🛑 Switched from POST to GET
    
            \Log::info("API Response Status: " . $response->status());
            \Log::info("API Response Body: " . $response->body());
    
            if ($response->successful()) {
                return $response->json()['access_token'] ?? null;
            }
    
            \Log::error('Failed to get access token: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            \Log::error('Exception in getApiAccessToken: ' . $e->getMessage());
            return null;
        }
    }
    
    public function createShipment($data)
    {
        $_token = $this->getApiAccessToken();
        
        // Ensure the base URL ends with a slash
        $this->baseUrl = rtrim($this->baseUrl, '/') . '/';
        
        // Ensure you're using the correct HTTP method as per API docs (POST or PUT)
        $response = Http::withOptions([
            'verify' => false,
        ])->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $_token,
        ])->get("{$this->baseUrl}createOrder", $data); // Change to PUT if required
        
        // Check if the response is successful
        if ($response->successful()) {
            return $response->json();
        }
        
        // Check for 405 method not allowed and provide more details
        if ($response->status() == 405) {
            throw new \Exception('Method not allowed: Please check the HTTP method and endpoint URL.');
        }
        
        // Throw an exception if the request failed for other reasons
        throw new \Exception('Failed to create shipment: ' . $response->body());
    }
    
    


    public function trackShipment($shipmentId)
    {
        $_token = $this->getApiAccessToken();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $_token,
        ])->get("{$this->baseUrl}/orderStatus");

        return $response->json();
    }

    public function cancelShipment($shipmentId)
    {
        $_token = $this->getApiAccessToken();
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $_token,
        ])->get("{$this->baseUrl}/shipments/{$shipmentId}");

        return $response->json();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartShipping;
use App\Services\TryotoService;
use App\Utils\CartManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TryotoController extends Controller
{
    private $tryotoService;

    public function __construct(TryotoService $tryotoService)
    {
        $this->tryotoService = $tryotoService;
    }

    public function getShippingOptions(Request $request)
    {

        try {
            $validatedData = $request->validate([
                'originCity' => 'nullable|array', // change this line to accept array
                'originCity.*' => 'string|nullable', // validate each item in the array
                'destinationCity' => 'required|string',
                'weight' => 'required',
                'height' => 'nullable|numeric',
                'width' => 'nullable|numeric',
                'length' => 'nullable|numeric',
            ]);
        

            $response = $this->tryotoService->getDeliveryFees($validatedData);

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Shipping options error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function createOrder(Request $request)
    {
        try {
            $orderData = $request->all(); // Validate and sanitize input as required.
            $response = $this->tryotoService->createOrder($orderData);
            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function createShipment(Request $request)
    {
        try {
            $orderId = $request->input('orderId');
            $deliveryOptionId = $request->input('deliveryOptionId');

            $response = $this->tryotoService->createShipment($orderId, $deliveryOptionId);
            return response()->json(['success' => true, 'data' => $response]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function trackShipment(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'trackingNumber' => 'required|string',
                'deliveryCompanyName' => 'required|string',
            ]);

            $trackingDetails = $this->tryotoService->trackShipment(
                $validatedData['trackingNumber'],
                $validatedData['deliveryCompanyName']
            );
            return response()->json(['success' => true, 'data' => $trackingDetails]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function getOrderTracking($orderId)
    {
        try {
            $tracking = $this->tryotoService->getOrderTracking($orderId);
            return response()->json($tracking);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createOrderWithShipping(Request $request)
    {
        // Log the incoming payload for debugging
        Log::info('Order creation payload:', $request->all());

        try {
            // 1. Validate the request
            $validatedData = $request->validate([
                'orderId' => 'required|string',
                'pickupLocationCode' => 'required|string',
                'createShipment' => 'required',
                'deliveryOptionId' => 'required|integer',
                'payment_method' => 'required|string',
                'amount' => 'required|numeric',
                'currency' => 'required|string',
                'customer' => 'required|array',
                'customer.name' => 'required|string',
                'customer.email' => 'required|email',
                'customer.mobile' => 'required|string',
                'customer.address' => 'required|string',
                'customer.district' => 'required|string',
                'customer.city' => 'required|string',
                'customer.country' => 'required|string',
                'items' => 'required|array',
                'items.*.name' => 'required|string',
                'items.*.price' => 'required|numeric',
                'items.*.quantity' => 'required|integer',
                'items.*.sku' => 'required|string'
            ]);

            // 2. Format orderId like shown in dashboard
            $timestamp = date('ymdHis');
            $validatedData['orderId'] = 'test' . $timestamp;

            // 3. Prepare order data
            $orderData = [
                'orderId' => $validatedData['orderId'],
                'pickupLocationCode' => $validatedData['pickupLocationCode'],
                'createShipment' => true, // Force boolean true
                'deliveryOptionId' => (int) $validatedData['deliveryOptionId'], // Ensure integer
                'payment_method' => $validatedData['payment_method'],
                'amount' => (float) $validatedData['amount'], // Ensure float
                'currency' => 'SAR', // Force SAR
                "customsValue" => (float) 12,
                'customsCurrency' => 'SAR', // Add customs currency
                'packageCount' => 1, // Add package count
                'packageWeight' => 1, // Add package weight
                'boxWidth' => 10, // Add dimensions
                'boxLength' => 10,
                'boxHeight' => 10,
                'orderDate' => date('d/m/Y H:i'), // Add order date
                'senderName' => 'Test Company', // Add sender name
                'customer' => [
                    'name' => $validatedData['customer']['name'],
                    'email' => $validatedData['customer']['email'],
                    'mobile' => $validatedData['customer']['mobile'],
                    'address' => $validatedData['customer']['address'],
                    'district' => $validatedData['customer']['district'],
                    'city' => $validatedData['customer']['city'],
                    'country' => $validatedData['customer']['country'],
                    'postcode' => '102030', // Add postcode
                    'refID' => '1000012' // Add refID
                ],
                'items' => array_map(function ($item) {
                    return [
                        'productId' => 90902, // Add productId
                        'name' => $item['name'],
                        'price' => (float) $item['price'],
                        'rowTotal' => (float) $item['price'] * (int) $item['quantity'],
                        'taxAmount' => 15, // Add tax amount
                        'quantity' => (int) $item['quantity'],
                        'sku' => $item['sku']
                    ];
                }, $validatedData['items'])
            ];
            Log::info('API Request:', ['data' => $orderData]);
            $response = $this->tryotoService->createOrder($orderData);
            Log::info('API Response:', ['response' => $response]);

            // 4. Make the API call to Tryoto service
            $response = $this->tryotoService->createOrder($orderData);


            // 5. Return a success response
            return response()->json([
                'success' => true,
                'data' => $response,
                'orderId' => $validatedData['orderId']
            ]);
        } catch (ValidationException $e) {
            // Log validation errors
            Log::error('Validation error:', ['errors' => $e->errors()]);

            // Return validation error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log generic errors
            Log::error('Order creation error:', ['error' => $e->getMessage()]);

            // Return error response
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function testOrderCreation()
    {

        try {
            $orderData = [
                "orderId" => "test" . time() . rand(1000, 9999),
                "pickupLocationCode" => "test",
                "createShipment" => true,
                "deliveryOptionId" => 2242,
                "payment_method" => "paid",
                "amount" => 10,
                "currency" => "SAR",
                "customsValue" => "12",
                "customsCurrency" => "SAR",
                "packageCount" => 2,
                "packageWeight" => 1,
                "boxWidth" => 10,
                "boxLength" => 10,
                "boxHeight" => 10,
                "orderDate" => date("d/m/Y H:i"),
                "senderName" => "Test Company",
                "customer" => [
                    "name" => "عبدالله الغامدي",
                    "email" => "test@test.com",
                    "mobile" => "546607389",
                    "address" => "6832, Abruq AR Rughamah District",
                    "district" => "Al Hamra",
                    "city" => "Riyadh",
                    "country" => "SA",
                    "postcode" => "202530",
                    "refID" => "1000012"
                ],
                "items" => [
                    [
                        "productId" => 303030,
                        "name" => "Test Item",
                        "price" => 100,
                        "rowTotal" => 100,
                        "taxAmount" => 15,
                        "quantity" => 1,
                        "sku" => "test-product"
                    ]
                ]
            ];



            $response = $this->tryotoService->createOrder($orderData);

            Log::info('Test order creation response:', ['response' => $response]);

            return response()->json([
                'success' => true,
                'data' => $response
            ]);
        } catch (\Exception $e) {
            Log::error('Test order creation failed:', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function updateShippingOption(Request $request)
    {



        // Validate the request
        $request->validate([
            'option_id' => 'required|integer',
            'price' => 'required',
            'chosen_shipping_id' => 'required',

        ]);
        $optionId = $request->input('option_id');
        $price = $request->input('price');
        $service_name = $request->input('service_name');
        $cart_group_id = $request->input('chosen_shipping_id');
        $totalQuantity = Cart::where('cart_group_id', $request->chosen_shipping_id)->where('is_checked', 1)->sum('quantity');

        log::info($request->all());

        $originalPrice = $price; // Store the original price
        $price *= 1.10; // Increase price by 10%
        $price = number_format($price, 2, '.', ''); // Format to 2 decimal places

        $shippingCommission = number_format($price - $originalPrice, 2, '.', ''); // Calculate the commission

        log::info($totalQuantity);

        $cart = CartShipping::updateOrCreate(
            ['cart_group_id' => $cart_group_id], // Condition to check existence
            [
                'shipping_method_id' => null,
                'option_id' => $optionId,
                'service_name' => $service_name,
                'shipping_cost' => $originalPrice,
                'shipping_comission' => $shippingCommission,
            ]
        );



        $chosenShipping = CartShipping::where('id', $request->chosen_shipping_id)->first();

        if ($chosenShipping) {

            $chosenShipping->option_id = $optionId;
            $chosenShipping->shipping_comission = $shippingCommission;
            $chosenShipping->service_name = $service_name;
            $chosenShipping->shipping_cost = $price;
            $chosenShipping->save();

            // Explicitly call the relationship and fetch the first cart
            $cart = $chosenShipping->cart()->first();

            if ($cart) {
                $cart->update(['shipping_cost' => $price]);
            }
        }



        // Process the shipping option (store in session, DB, etc.)
        session(['selected_shipping_option' => $optionId, 'selected_shipping_price' => $price]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Shipping option updated successfully!',
            'option_id' => $optionId,
            'price' => $price
        ]);
    }

}
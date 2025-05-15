<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;

class WebhookController extends Controller
{
    public function handleTryotoWebhook(Request $request)
    {
        // Validate webhook signature if you're using secretKey
        if ($secretKey = config('services.tryoto.webhook_secret')) {
            // Validate the webhook signature
            // Implementation depends on Tryoto's signature method
        }

        $payload = $request->all();
        Log::info('Tryoto Webhook received:', $payload);

        // Handle different webhook types
        switch ($payload['webhookType'] ?? 'orderStatus') {
            case 'orderStatus':
                return $this->handleOrderStatus($payload);
            case 'shipmentError':
                return $this->handleShipmentError($payload);
            default:
                return response()->json(['message' => 'Unhandled webhook type']);
        }
    }

    private function handleOrderStatus($payload)
    {
        // Update order status in your database
        $order = Order::where('order_id', $payload['orderId'])->first();
        if ($order) {
            $order->status = $payload['status'];
            $order->save();

            // You could also trigger notifications to customers here
        }

        return response()->json(['success' => true]);
    }

    private function handleShipmentError($payload)
    {
        // Log shipment errors and possibly notify admin
        Log::error('Shipment Error:', $payload);
        
        // You could send notifications to admin here
        
        return response()->json(['success' => true]);
    }
}
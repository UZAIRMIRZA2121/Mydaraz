<?php

use App\Http\Controllers\TryotoController;
use App\Http\Controllers\WebhookController;
use Illuminate\Http\Request;
use App\Http\Controllers\Shop\OrderController;

Route::middleware('api')->group(function () {
    Route::post('/shipping-options', [TryotoController::class, 'getShippingOptions']);
    Route::post('/create-order', [TryotoController::class, 'createOrderWithShipping']);
    Route::post('/tryoto-webhook', [WebhookController::class, 'handleTryotoWebhook']);
    Route::get('/order-tracking/{orderId}', [TryotoController::class, 'getOrderTracking']);
    Route::get('test-order-creation', [TryotoController::class, 'testOrderCreation']);
    Route::get('/order/awb/{orderId}', [OrderController::class, 'getAWB'])->name('order.awb');
});
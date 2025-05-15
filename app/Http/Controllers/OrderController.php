<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\TryotoService;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function __construct(private TryotoService $tryotoService) {}

    public function getAWB(string $orderId)
    {
        try {
            $awbData = $this->tryotoService->getAWB($orderId);
            return response()->json($awbData);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
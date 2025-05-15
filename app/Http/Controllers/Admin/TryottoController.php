<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Shop;
use App\Models\Tryotto;
use Illuminate\Http\Request;
use App\Models\Order;
use Brian2694\Toastr\Facades\Toastr;

use Illuminate\Support\Facades\Http;

class TryottoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::all();
        $paid_orders = Order::where('payment_status', 'paid')
            ->get();
        $unpaid_orders = Order::where('payment_status', 'Unpaid')
            ->get();

            $tryotto_total_paid_amount = Order::where('tryotto_payment_status', 'paid')
            ->get()
            ->sum(fn($order) => $order->shipping_cost - $order->shipping_commission);
        
            
        return view('admin-views.tryotto.list', compact(
            'orders',
            'paid_orders',
            'unpaid_orders',
            'tryotto_total_paid_amount',
        ));

    }

    public function payout(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:1'
        ]);
    
        // Find the order
        $order = Order::find($request->order_id);
    
        // Check if the order exists
        if (!$order) {
            Toastr::error(translate('Invalid access'));
            return back();
        }

        $order->update([
            'tryotto_payment_status' => 'paid',
            'tryotto_order_status' => 'delivered',
            'tryotto_transaction_ref' => '43234234324',
            'tryotto_payment_by' => 'seller',
            'tryotto_payment_method' => 'Tryotto',
        ]);
        
        
    
    
    
     
    Toastr::success(translate('Payout request created successfully.'));
    return back();
        // $orderId = $request->order_id;
        // $amount = $request->amount;

        // // Get PayTabs credentials from config
        // $paytabsProfileId = config('services.paytabs.profile_id');
        // $paytabsServerKey = config('services.paytabs.server_key');
        // $paytabsBaseUrl = config('services.paytabs.base_url');
        // $callbackUrl = config('services.paytabs.callback_url');
        // $returnUrl = config('services.paytabs.return_url');

        // // Ensure credentials are properly loaded
        // if (!$paytabsProfileId || !$paytabsServerKey) {
        //     return back()->with('error', 'PayTabs credentials are missing.');
        // }

        // // Prepare PayTabs request data
        // $payload = [
        //     "profile_id" => $paytabsProfileId,
        //     "tran_type" => "payout",  // Use "payout" for seller withdrawals
        //     "tran_class" => "ecom",
        //     "cart_id" => "PAYOUT_" . $orderId,
        //     "cart_currency" => "USD",
        //     "cart_amount" => $amount,
        //     "customer_details" => [
        //         "name" => "Seller Payout",
        //         "email" => "seller@example.com",
        //         "phone" => "1234567890",
        //         "street1" => "Seller Address",
        //         "city" => "Faisalabad",
        //         "state" => "Punjab",
        //         "country" => "PK",
        //         "zip" => "38000"
        //     ],
        //     "callback_url" => $callbackUrl,
        //     "return_url" => $returnUrl,
        // ];

        // $response = Http::withHeaders([
        //     'Authorization' => "Token " . config('services.paytabs.server_key'), // Ensure 'Token' is used instead of 'Bearer'
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json'
        // ])->post(config('services.paytabs.base_url') . "/request", $payload);

        // $responseData = $response->json();

        // // Debug API response
        // if (isset($responseData['redirect_url'])) {
        //     return redirect()->away($responseData['redirect_url']);
        // } else {
        //     dd($responseData); // Debugging line to check the response
        //     return back()->with('error', 'Failed to initiate payout: ' . ($responseData['message'] ?? 'Unknown error.'));
        // }
    }
    public function payoutCallback(Request $request)
    {
        // Handle PayTabs webhook callback for transaction status updates
        return response()->json(['status' => 'success']);
    }

    public function payoutSuccess()
    {
        return redirect()->route('orders.index')->with('success', 'Payout Successful!');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

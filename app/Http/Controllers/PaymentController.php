<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Promotion;
use Illuminate\Http\Request;
use App\Services\PayTabsService;

class PaymentController extends Controller
{
    protected $paytabsService;

    public function __construct(PayTabsService $paytabsService)
    {
        $this->paytabsService = $paytabsService;
    }

    public function createPayment(Request $request)
    {
        $order = [
            'cart_id' => uniqid(),
            'description' => 'Test Order',
            'amount' => 46.17,
            'customer_details' => [
                'name' => 'John Doe',
                'email' => 'johndoe@example.com',
                'phone' => '923001234567',
                'street1' => '123 Street',
                'city' => 'Lahore',
                'state' => 'PB',
                'country' => 'PK',
                'zip' => '54000',
                'ip' => request()->ip(),
            ]
        ];

        $paymentResponse = $this->paytabsService->createPayment($order);

        if (isset($paymentResponse['redirect_url'])) {
            return redirect()->away($paymentResponse['redirect_url']);
        }

        return response()->json($paymentResponse);
    }

    public function checkTransaction(Request $request , $tran_ref)
    {
     
        // Log the transaction reference
        \Log::info("Checking transaction with reference: " . ($tran_ref ?? 'N/A'));
        $tran_ref = session('tran_ref');
        if (!$tran_ref) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction reference is missing'
            ], 400);
        }
    
        $status = $this->paytabsService->checkTransactionStatus($tran_ref);
        return response()->json($status);
    }
    
    public function handleReturn(Request $request)
{
  
    $tranRef = session('tran_ref');
dd($tranRef);
    if (!$tranRef) {
        return view('vendor-views.payment.return', [
            'status' => 'error',
            'message' => 'Transaction reference is missing'
        ]);
    }

    \Log::info("Transaction Reference Received: " . $tranRef);

    $response = $this->paytabsService->checkTransactionStatus($tranRef);
    if (!isset($response['payment_result'])) {
        return view('vendor-views.payment.return', [
            'status' => 'error',
            'message' => 'Invalid response from PayTabs'
        ]);
    }

    if ($response['payment_result']['response_status'] == 'A') {
        // Remove transaction reference from session
        session()->forget('tran_ref');
    
        // Retrieve validated data from session
        $validatedData = session('banner_data', []);
    
        // Ensure session data exists before proceeding
        if (empty($validatedData)) {
            return redirect()->back()->with('error', 'Session expired. Please try again.');
        }
    
        // Handle image upload with a custom filename
        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = now()->format('Y-m-d') . '-' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('banner', $imageName, 'public');
        }
    
        $promotion = Promotion::find($validatedData['promotion_id']);
    
        // Create the banner
        $banner = Banner::create([
            'photo' => $imageName,
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
            'url' => $validatedData['url'],
            'published' => 1,
            'banner_type' => $promotion?->promotion_type, // Safe null check
            'resource_type' => $validatedData['resource_type'],
            'resource_id' => $validatedData['resource_type'] === 'category' ? $validatedData['category_id'] : $validatedData['product_id'],
            'status' => 1, // Default status to active
        ]);
    
        // Clear session after successful banner creation
        session()->forget('banner_data');
    
        return redirect()->route('banner.success')->with('success', 'Banner created successfully!');
    }
    

    \Log::info('PayTabs Response Data: ', $response);

    if (!isset($response['payment_result'])) {
        return view('vendor-views.payment.return', [
            'status' => 'error',
            'message' => 'Invalid response from PayTabs'
        ]);
    }

    $status = ($response['payment_result']['response_status'] === 'A') ? 'success' : 'failed';
    $message = ($status === 'success') ? 'Payment Successful!' : 'Payment Failed: ' . $response['payment_result']['response_message'];

    return view('vendor-views.payment.return', compact('status', 'message'));
}

    
}

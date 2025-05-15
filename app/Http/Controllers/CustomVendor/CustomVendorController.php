<?php

namespace App\Http\Controllers\CustomVendor;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Shop; // Adjust model name if different
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Services\SmsService;
use Log;
use Illuminate\Support\Facades\Mail;

use App\Traits\FileManagerTrait;
class CustomVendorController extends Controller
{

    use FileManagerTrait;
    protected $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
    // public function sendOtp(Request $request)
    // {
    //     // Validate phone and OTP inputs
    //     $validated = $request->validate([
    //         'phone' => 'required',  // Adjust regex for your phone number format
    //         'otp' => 'required|digits:6',  // Ensure OTP is exactly 6 digits
    //     ]);

    //     // Send OTP
    //     $response = $this->smsService->sendOTP($validated['phone'], $validated['otp']);

    //     // Check if there is an error in the response
    //     if (isset($response['error'])) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => $response['error']
    //         ], 400);
    //     }
    //     // If there is no error, return the success response
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'OTP sent successfully',
    //         'data' => $response
    //     ]);
    // }
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'phone' => 'required', // Saudi phone number validation
        ]);

        $phone = $validated['phone']; // Add country code
        $otp = rand(100000, 999999); // Generate a 6-digit OTP

        // Retrieve email from session
        $email = session('new_email');



        // Check if the phone number already exists using the Seller model
        $seller = Seller::where('email', $email)->first();
        session()->put('seller_id', $seller->id);
        if ($seller) {
            // Update the existing record with the new OTP
            $seller->otp = $otp;
            $seller->phone = $phone;
            $seller->save();
        }

        // Send OTP using the SMS service
        $response = $this->smsService->sendOTP($phone, $otp);

        // Handle SMS service response
        if (isset($response['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $response['error'],
            ], 400);
        }


        return response()->json([
            'status' => 'success',
            'message' => 'OTP sent successfully',
            'data' => $response,
        ]);
    }
    
    public function vendor_add(Request $request)
    {

        Log::info($request->all);
        // Validated the email to check if it already exists
        $email = $request->input('email_add');
        $existingSeller = Seller::where('email', $email)->first();

        // If the email exists, return a response indicating it's already taken
        if ($existingSeller) {
            return response()->json(['message' => 'Email is already taken.'], 400);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Create a new seller instance
        $seller = new Seller();
        $seller->email = $email;

        // Handle name parsing
        $fullName = $request->input('name_vendor');
        $nameParts = explode(' ', $fullName);
        $l_name = array_pop($nameParts);
        $seller->l_name = $l_name;
        $seller->f_name = implode(' ', $nameParts);

        // Set the OTP, phone, and password
        $seller->otp = $otp;
        $seller->phone = $request->input('phone_vendor');
        $seller->password = bcrypt($request->input('password_vendor'));

        // Send OTP
        $response = $this->smsService->sendOTP($request->input('phone_vendor'), $otp);

        // Check if there is an error in the response
        if (isset($response['error'])) {
            return response()->json([
                'status' => 'error',
                'message' => $response['error']
            ], 400);
        }

        // Check if there is an error in the response
        if (isset($response['error'])) {
            return response()->json(['message' => $response['error']], 400);
        } else {
            // If OTP is successfully sent, save the seller record
            $seller->save();

            // Store seller's ID in session
            session()->put('seller_id', $seller->id);

            // Return a response indicating the insertion was successful
            return response()->json(['status' => 'success', 'message' => 'Seller added successfully!', 'seller' => $seller]);
        }

    }


    public function checkShopName(Request $request)
    {
        $shopName = $request->input('shop_name');

        // Check if the shop name exists
        $exists = Shop::where('name', $shopName)->exists();

        return response()->json(['exists' => $exists]);
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'g-recaptcha-response' => 'required|captcha',
        // ]);

        $recaptchaResponse = $request->input('g-recaptcha-response');
        $secretKey = env('NOCAPTCHA_SECRET');
     
        $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
        ]);


        $responseBody = $response->json();

        if (!$responseBody['success']) {

            return back()->withErrors(['captcha' => 'reCAPTCHA verification failed.']);
        }




        // Retrieve the seller ID from the session
        $sellerId = session('seller_id');
        $seller = Seller::find($sellerId);

        if (!$seller) {
            return redirect()->back()->with('error', 'Seller not found!');
        }

        try {
            // Upload files and store file paths
            $filePaths = [
                'image' => $request->hasFile('image') ? $this->upload('shop/', 'webp', $request->file('image')) : null,
                'shop_logo' => $request->hasFile('shop_logo') ? $this->upload('shop/logos/', 'webp', $request->file('shop_logo')) : null,
                'shop_banner' => $request->hasFile('shop_banner') ? $this->upload('shop/banner/', 'webp', $request->file('shop_banner')) : null,
            ];

            // Update seller attributes
            $seller->fill([
                'radio_check' => $request->input('radio_check', $seller->radio_check),
                'business_day' => $request->input('businessName', $seller->businessName),
                'establishment' => $request->input('establishment', $seller->establishment),
                'city' => $request->input('city', $seller->city),
                'shop_address' => $request->input('shop_address', $seller->shop_address),
                'latitude' => $request->input('latitude', $seller->latitude),
                'longitude' => $request->input('longitude', $seller->longitude),
                'shop_name' => $request->input('shop_name', $seller->shop_name),
                'category' => $request->input('category', $seller->category),
                'brief_here' => $request->input('brief_here', $seller->brief_here),
                'upload_certifice' => $filePaths['upload_certifice'] ?? $seller->upload_certifice,
            ]);
            $seller->save();

            // Handle shop data
            $shop = Shop::updateOrCreate(
                ['seller_id' => $seller->id], // Match on seller ID
                [
                    'name' => $request->input('shop_name'),
                    'slug' => Str::slug($request->input('shop_name')), // Generate slug from shop name
                    'address' => $request->input('shop_address'),
                    'banner_storage_type' => 'public',
                    'banner' => $filePaths['shop_banner'] ?? null,
                    'image' => $filePaths['image'] ?? null,
                ]
            );

            // dd( 'Seller and shop updated successfully.', [
            //     'seller' => $seller,
            //     'shop' => $shop,
            // ]);
            // Custom email message
  // Get the email address
  $email = $seller->email;
  $status = $seller->status;

            $data = [
                'companyName' => htmlspecialchars(is_array($companyName = getWebConfig('company_name')) ? implode(', ', $companyName) : $companyName ?? ''),
                'companyLogo' => htmlspecialchars(is_array($companyLogo = getWebConfig('company_web_logo')) ? implode(', ', $companyLogo) : $companyLogo ?? ''),
                'title' => htmlspecialchars(is_array($title = $template['title'] ?? 'Default Title') ? implode(', ', $title) : $title),
                'body' => htmlspecialchars(is_array($body = $template['body'] ?? 'Default Body Content') ? implode(', ', $body) : $body),
                'copyrightText' => htmlspecialchars(is_array($copyrightText = $template['copyright_text'] ?? 'Default Copyright') ? implode(', ', $copyrightText) : $copyrightText),
                'footerText' => htmlspecialchars(is_array($footerText = $template['footer_text'] ?? 'Default Footer Text') ? implode(', ', $footerText) : $footerText),
                'buttonName' => htmlspecialchars(is_array($buttonName = $template['button_name'] ?? 'Default Button Name') ? implode(', ', $buttonName) : $buttonName),
                'status' => htmlspecialchars(is_array($status = $template['status'] ?? 'pending') ? implode(', ', $status) : $status),


            ];
            
            
          
            $mail = Mail::send('vendor-registration-pending', $data, function ($message) use ($email) {
                $message->to($email)
                    ->subject('Email Verification')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });
          

            // Clear all session data
            session()->flush();
            return redirect()->back()->with('success', 'Your form has been submitted successfully!');
        } catch (\Exception $e) {
            dd('Error updating seller or shop: ' . $e->getMessage(), ['trace' => $e->getTrace()]);
            // Log the error for debugging

            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
    }
    // Verify OTP
    public function verifyOtp(Request $request)
    {
        $otp = $request->input('otp');
        $sellerId = session()->get('seller_id');

        // Retrieve the seller record from the database
        $seller = Seller::find($sellerId);

        if ($seller && $seller->otp == $otp) {
            // OTP is correct, proceed with the next step
            return response()->json(['status' => 'success']);
        }

        // OTP is incorrect
        return response()->json(['status' => 'error'], 400);
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {

        $sellerId = session()->get('seller_id');
        $seller = Seller::find($sellerId);

        if (!$seller) {
            return response()->json(['message' => 'error'], 400);
        }

        // Generate a new OTP
        $newOtp = rand(100000, 999999);

        // Save the new OTP
        $seller->otp = $newOtp;
        $seller->save();

        // Optionally, send the OTP to the seller (via email or SMS)
        // For email: Use Laravel's Mail facade to send the OTP
        // Mail::to($seller->email)->send(new \App\Mail\OtpMail($newOtp));

        // Return success response
        return response()->json(['message' => 'success']);
    }

}

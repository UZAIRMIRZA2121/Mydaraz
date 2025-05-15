<?php

namespace App\Http\Controllers\Vendor\Auth;

use App\Models\BusinessSetting;
use App\Models\Chatting;
use Illuminate\Support\Str;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Brian2694\Toastr\Facades\Toastr;

use Laravel\Socialite\Facades\Socialite;

class SocialControllerFacebook extends Controller
{
    /**
     * Redirect to Facebook authentication page
     */
    public function facebookRedirect()
    {
        // Determine if the request is from "vendor" or "customer"
        $userType = request()->segment(1); // Get 'vendor' or 'customer' from the URL
    
        // Generate dynamic redirect URI using APP_URL
        $redirectUri = config('app.url') . "$userType/facebook/callback"; // Corrected URL format
    
        // Override the redirect URI for this request
        config(['services.facebook.redirect' => $redirectUri]);
    
        // Debugging output
        // dd(config('services.facebook.redirect'));
    
        return Socialite::driver('facebook')->redirect();
    }
    

    /**
     * Handle Facebook login callback
     */
    public function loginWithFacebook()
    {
           
              // Generate dynamic redirect URI using APP_URL
              $redirectUri = config('app.url') . "vendor/facebook/callback"; // Corrected URL format
          
              // Override the redirect URI for this request
              config(['services.facebook.redirect' => $redirectUri]);
          
          
        try {
            // Retrieve user from Facebook
            $user = Socialite::driver('facebook')->stateless()->user();
        
            // Ensure email exists, otherwise, generate a placeholder
            $email = $user->getEmail() ?? 'fb_user_' . $user->getId() . '@facebook.com';
         
            // Check if user already exists
            $existingUser = Seller::where('facebook_id', $user->getId())->orWhere('email', $email)->first();
            if ($existingUser && $existingUser->status == 'approved') {
                Auth::guard('seller')->login($existingUser);

                Toastr::info(translate('welcome_to_your_dashboard') . '.');
                return redirect()->route('vendor.dashboard.index');
            }

            if ($existingUser) {
                session(['new_email' => $existingUser->email]);
                return redirect()->route('vendor.auth.registration.index');
            } else {
                // Create a new user
                $uuid = Str::uuid()->toString();
                $newUser = Seller::create([
                    'f_name' => $user->name,
                    'email' => $user->email,
                    'image' => $user->avatar,
                    'facebook_id' => $user->id,
                    'password' => Hash::make($uuid . now())  // Password will be hashed
                ]);
                session(['new_email' => $user->email]);
                  // Start chat with user
            $this->startChatting($user->id);

                return redirect()->route('vendor.auth.registration.index');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('vendor.auth.login')->with('error', 'Facebook login failed. Please try again.');
        }
    }
    private function startChatting($receiverId)
    {
        Chatting::create([
            'seller_id' => $receiverId,
            'admin_id' => 1,
            'message' => 'Welcome to ALBAZAR',
            'sent_by_admin' => 1,
            'seen_by_admin' => 1,
            'seen_by_seller' => 0,
            'status' => 1,
            'notification_receiver' => 'seller',
        ]);
    }

    /**
     * Configure Facebook login dynamically
     */
    private function configureFacebook()
    {
        $business_setting = BusinessSetting::where('type', 'social_login')->first();
        $service = 'facebook';

        if ($business_setting) {
            $socialLogins = json_decode($business_setting->value, true);

            foreach ($socialLogins as $socialLogin) {
                if ($socialLogin['status'] == 1 && $socialLogin['login_medium'] == $service) {
                    config([
                        "services.{$service}.client_id" => $socialLogin['client_id'],
                        "services.{$service}.client_secret" => $socialLogin['client_secret'],
                        "services.{$service}.redirect" => url("/vendor/auth/facebook/callback"),
                    ]);
                }
            }
        }
    }
}

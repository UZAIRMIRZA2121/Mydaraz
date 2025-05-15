<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialLoginController extends Controller
{
    // Redirect to Google
    public function redirectToGoogle()
    {
       
        return Socialite::driver('google')->redirect();
    }

    // Handle Google Callback
    public function handleGoogleCallback()
    {
       
        $googleUser = Socialite::driver('google')->user();
        
        // Logic for checking or creating a user
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str_random(16)) // Or any default password logic
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('home'); // Redirect to the home page after login
    }

    // Redirect to Facebook
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    // Handle Facebook Callback
    public function handleFacebookCallback()
    {
        $facebookUser = Socialite::driver('facebook')->user();
        
        // Logic for checking or creating a user
        $user = User::where('email', $facebookUser->getEmail())->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $facebookUser->getName(),
                'email' => $facebookUser->getEmail(),
                'facebook_id' => $facebookUser->getId(),
                'password' => bcrypt(str_random(16)) // Or any default password logic
            ]);
        }

        Auth::login($user, true);

        return redirect()->route('home'); // Redirect to the home page after login
    }
}

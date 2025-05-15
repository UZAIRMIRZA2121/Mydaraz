<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SmsService
{
    private $app_id = "URk9Pg3lrBHSvEGJIZiEyDgCwOS2V8uvGfciiKrx";
    private $app_sec = "Lniu0LFDVk0EN0D9k6cBx3mv8E97xfSAY9PTamKPouUob0RD6IIJgkDJFBPNoU5bwRQTCIU1yaZXITcppaMdpWGnlSkYnvaD8mle";

    public function sendOTP($phone, $otp)
    {
        $app_hash = base64_encode("{$this->app_id}:{$this->app_sec}");

        $messages = [
            "messages" => [
                [
                    "text" => "Your OTP code is: {$otp}. Valid for 5 minutes.",
                    "numbers" => [$phone],
                    "sender" => "albazar"
                ]
            ]
        ];

        $url = "https://api-sms.4jawaly.com/api/v1/account/area/sms/send";
        $headers = [
            "Accept" => "application/json",
            "Content-Type" => "application/json",
            "Authorization" => "Basic {$app_hash}"
        ];

        // Send the POST request using Laravel's Http facade
        $response = Http::withHeaders($headers)
            ->post($url, $messages);

        // Return the decoded response
        return $response->json();
    }
}

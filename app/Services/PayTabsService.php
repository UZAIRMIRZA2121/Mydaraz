<?php

namespace App\Services;

use GuzzleHttp\Client;

class PayTabsService
{
    protected $client;
    protected $serverKey;
    protected $profileId;
    protected $merchantId;
    protected $baseUrl;
    protected $callbackUrl;
    protected $returnUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->serverKey = config('services.paytabs.server_key');
        $this->profileId = config('services.paytabs.profile_id');
        $this->merchantId = config('services.paytabs.merchant_id');
        $this->baseUrl = config('services.paytabs.base_url');
        $this->callbackUrl = config('services.paytabs.callback_url');
        $this->returnUrl = config('services.paytabs.return_url');
    }

    public function createPayment($order)
    {


        $response = $this->client->post("https://secure-global.paytabs.com/payment/request", [
            'headers' => [
                'authorization' => $this->serverKey,
                'content-type' => 'application/json',
            ],
            'json' => [
                'profile_id' => $this->profileId,
                'tran_type' => 'sale',
                'tran_class' => 'ecom',
                'cart_id' => $order['cart_id'],
                'cart_description' => $order['description'],
                'cart_currency' => 'PKR',
                'cart_amount' => $order['amount'],
                'callback' => $this->callbackUrl,
                'return' => $order['return'],
                'customer_details' => $order['customer_details'],
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function checkTransactionStatus($tran_ref)
    {
        $response = $this->client->post("https://secure-global.paytabs.com/payment/query", [
            'headers' => [
                'authorization' => $this->serverKey,
                'content-type' => 'application/json',
            ],
            'json' => [
                'profile_id' => $this->profileId,
                'tran_ref' => $tran_ref,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}

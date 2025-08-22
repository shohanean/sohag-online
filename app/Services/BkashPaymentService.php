<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class BkashPaymentService
{
    private $baseUrl;
    private $appKey;
    private $appSecret;
    private $username;
    private $password;

    public function __construct()
    {
        $this->baseUrl = config('bkash.base_url', env('BKASH_BASE_URL'));
        $this->appKey = env('BKASH_APP_KEY');
        $this->appSecret = env('BKASH_APP_SECRET');
        $this->username = env('BKASH_USERNAME');
        $this->password = env('BKASH_PASSWORD');
    }

    private function getToken()
    {
        $response = Http::withHeaders([
            'username' => $this->username,
            'password' => $this->password,
        ])->post("{$this->baseUrl}/tokenized/checkout/token/grant", [
            'app_key' => $this->appKey,
            'app_secret' => $this->appSecret,
        ]);

        return $response->json();
    }

    public function createPayment($amount, $invoice)
    {
        $token = $this->getToken();

        $response = Http::withToken($token['id_token'])
            ->withHeaders(['X-APP-Key' => $this->appKey])
            ->post("{$this->baseUrl}/tokenized/checkout/create", [
                'mode' => '0011',
                'payerReference' => ' ',
                'callbackURL' => env('BKASH_CALLBACK_URL'),
                'amount' => $amount,
                'currency' => 'BDT',
                'intent' => 'sale',
                'merchantInvoiceNumber' => $invoice,
            ]);

        return $response->json();
    }
    public function executePayment($paymentID)
    {
        $token = $this->getToken();

        $response = Http::withToken($token['id_token'])
            ->withHeaders(['X-APP-Key' => $this->appKey])
            ->post("{$this->baseUrl}/tokenized/checkout/execute", [
                'paymentID' => $paymentID,
            ]);

        return $response->json();
    }
}

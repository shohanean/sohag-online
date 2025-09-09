<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use UddoktaPay\LaravelSDK\UddoktaPay;
use UddoktaPay\LaravelSDK\Requests\CheckoutRequest;
use App\Models\{Subscription, Subscription_fee, User};

class UddoktaPayController extends Controller
{
    public function checkout(Subscription $subscription)
    {
        $user = User::find($subscription->user_id);
        $uddoktapay = UddoktaPay::make(env('UDDOKTAPAY_API_KEY'), env('UDDOKTAPAY_API_URL'));
        try {
            $checkoutRequest = CheckoutRequest::make()
                ->setFullName($user->name)
                ->setEmail($user->email)
                // ->setAmount($subscription->package_price)
                ->setAmount(1)
                ->addMetadata('subscription_id', $subscription->id)
                ->setRedirectUrl(env('UDDOKTAPAY_CALLBACK_URL'))
                ->setCancelUrl(route('uddoktapay.cancel'))
                ->setWebhookUrl(route('uddoktapay.ipn'));

            $response = $uddoktapay->checkout($checkoutRequest);

            if ($response->failed()) {
                dd($response->message());
            }

            return redirect($response->paymentURL());
        } catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
            dd("Initialization Error: " . $e->getMessage());
        }
    }
    public function verify(Request $request)
    {
        $uddoktapay = UddoktaPay::make(env('UDDOKTAPAY_API_KEY'), env('UDDOKTAPAY_API_URL'));
        try {
            $response = $uddoktapay->verify($request);

            if ($response->success()) {
                // Handle successful status
                $success_response = json_decode(json_encode($response->toArray()));
                $subscription = Subscription::find($success_response->metadata->subscription_id);
                Subscription_fee::create([
                    'subscription_id' => $subscription->id,
                    'user_id' => $subscription->user_id,
                    'package_id' => $subscription->package_id,
                    'package_name' => $subscription->package_name,
                    'package_price' => $subscription->package_price,
                    'generated_date' => Carbon::now()->toDateString(),
                    'due_date' => $subscription->billing_date->toDateString(),
                    'paid_date' => Carbon::now()->toDateString(),
                    'status' => 'paid',
                    'generated_by' => $subscription->user_id,
                ]);
                $subscription->billing_date = $subscription->billing_date->addMonthNoOverflow()->toDateString();
                $subscription->save();

                return "very good";
                // return redirect()
                //     ->route('subscription.details', ['subscription_id' => $subscription->id])
                //     ->with('success', 'Subscription payment successfully done!');
            } elseif ($response->pending()) {
                // Handle pending status
                return "payment pending";
            } elseif ($response->failed()) {
                // Handle failure
                return "payment failed";
            }
        } catch (\UddoktaPay\LaravelSDK\Exceptions\UddoktaPayException $e) {
            dd("Verification Error: " . $e->getMessage());
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Services\BkashPaymentService;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\Subscription_fee;

class BkashController extends Controller
{
    public function test()
    {
        return "asd";
    }
    public function pay(BkashPaymentService $bkash, Subscription $subscription)
    {
        $invoice = 'SHOHAN-' . time();
        $payment = $bkash->createPayment($subscription->package_price, $invoice);
        // $payment = $bkash->createPayment(Subscription_fee::findOrFail($subscription_fee_id)->package_price, $invoice, [
        //     'merchantInvoiceNumber' => $invoice, // Pass your invoice
        // ]);
        if (isset($payment['bkashURL'])) {
            // Redirect user to bKash sandbox payment page
            return redirect()->away($payment['bkashURL']);
        }

        return response()->json([
            'error' => $payment['statusMessage'] ?? 'Something went wrong',
        ]);
    }

    public function callback(Request $request, BkashPaymentService $bkash)
    {
        // return $invoice = $request->input('merchantInvoiceNumber'); // your original invoice
        $status = $request->input('status');      // works for GET & POST
        $paymentID = $request->input('paymentID'); // works for GET & POST

        if ($status === 'success') {
            $execute = $bkash->executePayment($paymentID);
            if (isset($execute['transactionStatus']) && $execute['transactionStatus'] === 'Completed') {
                // When everythings goes right
                // return $request;
                // return $invoice = $request->input('merchantInvoiceNumber');
                // return $response = $bkash->searchPayment($execute['trxID']);
                return "Payment Successful! Transaction ID: " . $execute['trxID'];
            }
            return "Payment execution failed. " . ($execute['statusMessage'] ?? 'Unknown error');
        }

        if ($status === 'failure') return "Payment Failed!";
        if ($status === 'cancel') return "Payment Cancelled!";

        return "Unknown Response";
    }
}

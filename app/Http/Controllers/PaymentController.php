<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client_wallet;
use App\Models\Payment;

class PaymentController extends Controller
{
    public function index($user_id)
    {
        $client_wallet = Client_wallet::with('user')->where('user_id', $user_id)->first();
        $payments = Payment::where('user_id', $user_id)->latest()->get();
        return view('backend.payment.index', compact('client_wallet', 'payments'));
    }
    public function store(Request $request, $client_wallet_id)
    {
        if ($request->source) {
            $status = 'pending';
        } else {
            $status = 'approved';
        }
        $request->validate([
            'payment_amount' => 'required'
        ]);
        $user_id = Client_wallet::find($client_wallet_id)->user_id;
        Payment::create($request->except('_token', 'source') + [
            'user_id' => $user_id,
            'client_wallet_id' => $client_wallet_id,
            'status' => $status,
            'added_by' => auth()->id()
        ]);
        if (empty($request->source)) {
            //now impact on client wallet
            Client_wallet::where('user_id', $user_id)->increment('paid', $request->payment_amount);
            Client_wallet::where('user_id', $user_id)->decrement('due', $request->payment_amount);
        }
        return back()->with('success', 'Payment added successfully!');
    }
    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_amount' => 'required'
        ]);
        if ($payment->status == 'approved') {
            //first take db value and decrement paid & increment due
            Client_wallet::where('user_id', $payment->user_id)->decrement('paid', $payment->payment_amount);
            Client_wallet::where('user_id', $payment->user_id)->increment('due', $payment->payment_amount);
            //second take user value and increment paid & decrement due
            Client_wallet::where('user_id', $payment->user_id)->increment('paid', $request->payment_amount);
            Client_wallet::where('user_id', $payment->user_id)->decrement('due', $request->payment_amount);
        }
        //lastly update payment history
        $payment->payment_amount = $request->payment_amount;
        $payment->save();
        return back()->with('update_success', 'Payment updated successfully!');
    }
    public function payment_status_change(Payment $payment)
    {
        $payment->status = 'approved';
        $payment->save();
        Client_wallet::find($payment->client_wallet_id)->increment('paid', $payment->payment_amount);
        Client_wallet::find($payment->client_wallet_id)->decrement('due', $payment->payment_amount);
        return back()->with('update_success', 'Payment updated successfully!');
    }
    public function destroy(Payment $payment)
    {
        if ($payment->status == 'approved') {
            Client_wallet::find($payment->client_wallet_id)->increment('due', $payment->payment_amount);
            Client_wallet::find($payment->client_wallet_id)->decrement('paid', $payment->payment_amount);
        }
        $payment->delete();
        return back()->with('delete_success', 'Payment deleted successfully!');
    }
}

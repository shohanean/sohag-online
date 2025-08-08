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
        $request->validate([
            'payment_amount' => 'required'
        ]);
        $user_id = Client_wallet::find($client_wallet_id)->user_id;
        Payment::create($request->except('_token') + [
            'user_id' => $user_id,
            'client_wallet_id' => $client_wallet_id,
            'status' => 'approved',
            'added_by' => auth()->id()
        ]);
        //now impact on client wallet
        Client_wallet::where('user_id', $user_id)->increment('paid', $request->payment_amount);
        Client_wallet::where('user_id', $user_id)->decrement('due', $request->payment_amount);
        return back()->with('success', 'Payment added successfully!');
    }
}

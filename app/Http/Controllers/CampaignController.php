<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Page;
use App\Models\User;
use App\Models\Dollar_rate;
use App\Models\Transection;
use App\Models\Client_wallet;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (auth()->user()->roles->first()->name !== 'Super Admin') {
                abort(404);
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // return User::role('Client')->count();
        // return $campaigns = Campaign::with('user')->latest()->get()->groupBy('user_id');
        $campaigns = Campaign::with('user')->latest()->get()->groupBy('user_id');
        return view('backend.campaign.index', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pages = Page::select('user_id', 'page_name', 'id')->with('user')
            ->latest()
            ->orderBy('user_id')
            ->get()
            ->groupBy('user_id');
        return view('backend.campaign.create', compact('pages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'page_id' => 'required',
            'name' => 'required'
        ]);
        Campaign::create([
            'name' => $request->name,
            'ad_id' => $request->ad_id,
            'user_id' => Page::find($request->page_id)->user_id,
            'page_id' => $request->page_id,
            'added_by' => auth()->id(),
        ]);
        return back()->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {
        $campaigns = Campaign::where('user_id', $user_id)->latest()->get();
        $pages = Page::where('user_id', $user_id)->get();
        return view('backend.campaign.show', compact('campaigns', 'pages'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        $transections = Transection::where('campaign_id', $campaign->id)->latest()->get();
        $dollar_rate = Dollar_rate::latest()->first();
        if (!$dollar_rate) {
            return "Set Dollar Rate First";
        }
        return view('backend.campaign.edit', compact('campaign', 'dollar_rate', 'transections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'campaign_name' => 'required',
            'ad_id' => 'required'
        ]);
        $campaign->name = $request->campaign_name;
        $campaign->ad_id = $request->ad_id;
        $campaign->save();
        return back()->with('update_success', 'Campaign updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy($transection_id)
    {
        $transection = Transection::findOrFail($transection_id);
        $campaign = Campaign::findOrFail($transection->campaign_id);
        $campaign->decrement('total', $transection->amount);
        Client_wallet::where('user_id', $campaign->user_id)->decrementEach([
            'total' => $transection->amount,
            'due' => $transection->amount,
        ]);
        Transection::findOrFail($transection_id)->forceDelete();
        return redirect()->back()->with('delete_success', 'Payment details permanently deleted.');
    }

    public function add_expense(Campaign $campaign, Request $request)
    {
        $request->validate([
            'amount' => 'required',
            'date' => 'required'
        ]);

        $old_history = Transection::where([
            ['campaign_id', '=', $campaign->id],
            ['transaction_type', '=', 'expense'],
        ])->latest()->first();
        if (empty($old_history)) {
            //means there are old entry of this campaign
            $old_amount = 0;
        } else {
            //means there are no entry of this campaign
            $old_amount = $old_history->current_amount;
        }
        $current_amount = $request->amount;
        $spent_amount = $current_amount - $old_amount;
        $dollar_rate = Dollar_rate::latest()->first()->rate;
        Transection::create([
            'user_id' => $campaign->user_id,
            'date' => $request->date,
            'page_id' => $campaign->page_id,
            'campaign_id' => $campaign->id,
            'old_amount' => $old_amount,
            'current_amount' => $current_amount,
            'spent_amount' => $spent_amount,
            'dollar_rate' => $dollar_rate,
            'amount' => $dollar_rate * $spent_amount,
            'transaction_type' => 'expense',
            'added_id' => auth()->id(),
        ]);
        $campaign->increment('total', $dollar_rate * $spent_amount);
        // update to client wallet
        Client_wallet::where('user_id', $campaign->user_id)->incrementEach([
            'total' => $dollar_rate * $spent_amount,
            'due' => $dollar_rate * $spent_amount,
        ]);
        return back()->with('success', 'Expense added successfully!');
    }
    public function add_payment(Campaign $campaign, Request $request)
    {
        $request->validate([
            'pamount' => 'required',
            'pdate' => 'required'
        ]);
        Transection::create([
            'user_id' => $campaign->user_id,
            'date' => $request->pdate,
            'page_id' => $campaign->page_id,
            'campaign_id' => $campaign->id,
            //these are nullable
            // 'old_amount' => $old_amount,
            // 'current_amount' => $current_amount,
            // 'spent_amount' => $spent_amount,
            // 'dollar_rate' => $dollar_rate,
            'amount' => $request->pamount,
            'transaction_type' => 'payment',
            'added_id' => auth()->id(),
        ]);
        $campaign->increment('paid', $request->pamount);
        $campaign->decrement('due', $request->pamount);
        return back()->with('psuccess', 'Payment added successfully!');
    }
    public function change_running_status(Campaign $campaign)
    {
        $campaign->running_status = !$campaign->running_status;
        $campaign->save();
        return back();
    }
}

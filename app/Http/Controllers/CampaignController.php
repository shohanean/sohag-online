<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\Page;
use App\Models\Dollar_rate;
use App\Models\Transection;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $campaigns = Campaign::latest()->get();
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
            'name' => 'required'
        ]);
        Campaign::create([
            'name' => $request->name,
            'user_id' => Page::find($request->page_id)->user_id,
            'page_id' => $request->page_id,
            'added_by' => auth()->id(),
        ]);
        return redirect()->route('campaign.create')->with('success', 'Campaign created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function show(Campaign $campaign)
    {
        $transections = Transection::where('campaign_id', $campaign->id)->latest()->get();
        $dollar_rate = Dollar_rate::latest()->first();
        if (!$dollar_rate) {
            return "Set Dollar Rate First";
        }
        return view('backend.campaign.show', compact('campaign', 'dollar_rate', 'transections'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function edit(Campaign $campaign)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Campaign  $campaign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campaign $campaign)
    {
        //
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
        $campaign->increment('due', $dollar_rate * $spent_amount);
        return back()->with('success', 'Expense added successfully!');
    }

    public function add_payment(Campaign $campaign, Request $request)
    {
        $request->validate([
            'pamount' => 'required',
            'pdate' => 'required'
        ]);

        return $request;
        // Transection::create([
        //     'user_id' => $campaign->user_id,
        //     'date' => $request->date,
        //     'page_id' => $campaign->page_id,
        //     'campaign_id' => $campaign->id,
        //     'old_amount' => $old_amount,
        //     'current_amount' => $current_amount,
        //     'spent_amount' => $spent_amount,
        //     'dollar_rate' => $dollar_rate,
        //     'amount' => $dollar_rate * $spent_amount,
        //     'transaction_type' => 'expense',
        //     'added_id' => auth()->id(),
        // ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Page, Campaign, Client_wallet, Transection, Payment};

class ClientController extends Controller
{
    function page_details($page_id)
    {
        $page = Page::findOrFail($page_id);
        if ($page->user_id != auth()->id()) {
            abort(404);
        }
        $payments = Payment::where('user_id', $page->user_id)->latest()->get();
        $campaigns = Campaign::where('page_id', $page->id)->get();
        $client_wallet = Client_wallet::where('user_id', $page->user_id)->first();
        return view('backend.client.pagedetails', compact('page', 'payments','campaigns', 'client_wallet'));
    }
    function campaign_details($campaign_id)
    {
        $campaign = Campaign::find($campaign_id);
        $transections = Transection::where('campaign_id', $campaign_id)->latest()->get();
        return view('backend.client.campaigndetails', compact('campaign', 'transections'));
    }
}

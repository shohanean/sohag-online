<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{Page, Campaign};

class ClientController extends Controller
{
    function page_details($page_id){
        $page = Page::findOrFail($page_id);
        if ($page->user_id != auth()->id()) {
            abort(404);
        }
        $campaigns = Campaign::where('page_id', $page->id)->get();
        return view('backend.client.pagedetails', compact('page', 'campaigns'));
    }
}

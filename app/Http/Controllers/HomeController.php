<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\Dollar_rate;
use App\Models\Client_wallet;
use App\Models\{Campaign, Page, Package, Subscription, Subscription_fee};
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Permission::create([
        //   'name' => 'can restore user'
        // ]);
        // User::find(1)->assignRole('Super Admin');
        $active_clients = Role::where('name', 'Client')->first()->users()->get();
        $users = User::latest()->get();
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        $total_campaigns = Campaign::count();
        $client_wallet = Client_wallet::where('user_id', auth()->id())->first();
        $pages = Page::all();
        $subscriptions = Subscription::paginate(10);
        $user_subscriptions = Subscription::where('user_id', auth()->id())->latest()->get();
        return view('home', compact('subscriptions', 'user_subscriptions', 'active_clients', 'users', 'campaigns', 'total_campaigns', 'client_wallet', 'pages'));
    }
    public function import(Request $request)
    {
        Excel::import(new UsersImport, $request->file('import'));
        return back();
    }
    public function dollar_rate()
    {
        return view('backend.misc.dollar_rate', [
            'dollar_rates' => Dollar_rate::latest()->get()
        ]);
    }
    public function dollar_rate_insert(Request $request)
    {
        $request->validate([
            'rate' => 'required|numeric',
        ]);
        Dollar_rate::create([
            'rate' => $request->rate,
            'date' => Carbon::now()
        ]);
        return back()->with('success', 'Dollar rate saved successfully.');
    }
}

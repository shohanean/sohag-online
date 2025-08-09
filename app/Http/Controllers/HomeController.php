<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\Dollar_rate;
use App\Models\Client_wallet;
use App\Models\Campaign;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;

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
        $users = User::latest()->paginate(10);
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        $client_wallet = Client_wallet::where('user_id', auth()->id())->first();
        return view('home', compact('users', 'campaigns', 'client_wallet'));
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Imports\UsersImport;
use App\Models\Dollar_rate;
use App\Models\Client_wallet;
use App\Models\{Campaign, Page, Package, Subscription, Subscription_fee};
use App\Models\Server;
use App\Models\Payment_notification;
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
        // foreach (Subscription_fee::all() as $key => $subscription_fee) {
        //     $subscription_fee->paid_date = $subscription_fee->generated_date;
        //     $subscription_fee->status = 'paid';
        //     $subscription_fee->save();
        // }

        // foreach (Subscription::all() as $key => $subscription) {
        //     Subscription::where('id', $subscription->id)->update([
        //         'billing_date' => $subscription->created_at
        //     ]);
        // }
        // Server::create([
        //     'name' => 'Cloud Server 03'
        // ]);
        // Permission::create([
        //   'name' => 'can manage subscription'
        // ]);
        // User::find(1)->assignRole('Super Admin');
        $payment_notifications = Payment_notification::latest()->get();
        $active_clients = Role::where('name', 'Client')->first()->users()->get();
        $users = User::latest()->get();
        $campaigns = Campaign::where('user_id', auth()->id())->get();
        $total_campaigns = Campaign::count();
        $client_wallet = Client_wallet::where('user_id', auth()->id())->first();
        $pages = Page::all();
        $subscriptions = Subscription::paginate(10);
        $user_subscriptions = Subscription::where('user_id', auth()->id())->latest()->get();
        $servers = Server::latest()->get();
        $restricted_for_payments = Campaign::with('user')->latest()->get()->groupBy('user_id');

        return view('home', compact('restricted_for_payments', 'payment_notifications', 'servers', 'subscriptions', 'user_subscriptions', 'active_clients', 'users', 'campaigns', 'total_campaigns', 'client_wallet', 'pages'));
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
    public function active_clients()
    {
        return view('backend.misc.active_clients', [
            'clients' => User::role('Client')->get()
        ]);
    }
    public function change_client_info(User $user, Request $request)
    {
        $request->validate([
            'new_name' => 'required',
            'new_email' => 'required|email|unique:users,email,' . $user->id,
        ]);
        $user->name = $request->new_name;
        $user->email = $request->new_email;
        $user->password = bcrypt($request->new_password);
        $user->save();

        //changing the page name
        Page::find($request->page_id)->update([
            'page_name' => $request->new_page_name
        ]);
        return back()->with('success', 'Client Information Changed Successfully!');
    }
    public function subscriptions()
    {
        return view('backend.misc.subscriptions', [
            'clients' => User::role('Client')->get()
        ]);
    }
    public function subscriptions_list(User $user)
    {
        $servers = Server::all();
        $packages = Package::all();
        return view('backend.misc.subscriptions_list', compact('user', 'servers', 'packages'));
    }
    public function subscriptions_list_details(Subscription $subscription)
    {
        return $subscription->subscription_fees;
    }
    public function subscription_store(Request $request)
    {
        $package = Package::findOrFail($request->package_id);
        Subscription::create([
            'user_id' => $request->user_id,
            'package_id' => $request->package_id,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'server_id' => $request->server_id,
            'domain_name' => $request->domain_name,
            'billing_date' => Carbon::now()->addMonthNoOverflow()->toDateString()
        ]);
        return back()->with('success', 'Subscription Added Successfully!');
    }
    public function subscription_update(Subscription $subscription, Request $request)
    {
        $package = Package::findOrFail($request->package_id);
        $subscription->package_id = $package->id;
        $subscription->package_name = $package->name;
        $subscription->package_price = $package->price;
        $subscription->server_id = $request->server_id;
        $subscription->domain_name = $request->domain_name;
        $subscription->billing_date = $request->billing_date;
        $subscription->save();
        return back()->with('update_success', 'Subscription Information Updated Successfully!');
    }
    public function subscription_destroy(Subscription $subscription)
    {
        $subscription->delete();
        return back()->with('delete_success', 'Subscription Deleted Successfully!');
    }
    public function upcoming_subscriptions(Request $request)
    {
        if($request->date_range){
            [$start, $end] = explode(' - ', $request->date_range);
            $startDate = Carbon::parse($start)->toDateString();
            $endDate = Carbon::parse($end)->toDateString();
            $subscriptions = Subscription::whereBetween('billing_date', [$startDate, $endDate])->orderBy('billing_date', 'asc')->get();
        }else{
            $subscriptions = Subscription::orderBy('billing_date', 'asc')->get();
        }
        return view('backend.misc.upcoming_subscriptions', [
            'subscriptions' => $subscriptions
        ]);
    }
    public function server()
    {
        return view('backend.misc.server', [
            'servers' => Server::latest()->get()
        ]);
    }
    public function server_update(Request $request, Server $server)
    {
        $request->validate([
            'name' => 'required|unique:servers,name,' . $server->id,
        ]);
        $server->update($request->only('name'));
        return back()->with('update_success', 'Server Name Changed Successfully!');
    }
    public function server_destroy(Server $server)
    {
        $server->delete();
        return back()->with('delete_success', 'Server Deleted Successfully!');
    }
    public function add_server(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:servers,name',
        ]);
        Server::create([
            'name' => $request->name
        ]);
        return back()->with('success', 'Server Added Successfully!');
    }
    public function payment_notification_destroy(Payment_notification $payment_notification)
    {
        $payment_notification->delete();
        return back();
    }
}

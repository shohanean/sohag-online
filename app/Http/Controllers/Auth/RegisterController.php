<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Page;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\Subscription_fee;
use App\Models\Client_wallet;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
        ]);
        Page::create([
            'user_id' => $user->id,
            'page_name' => $data['page_name'],
        ]);
        $user->assignRole('Client');
        Client_wallet::create([
            'user_id' => $user->id
        ]);
        $package = Package::find(1);
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'package_price' => $package->price,
        ]);
        Subscription_fee::create([
            'subscription_id' => $subscription->id,
            'user_id' => $user->id,
            'package_id' => $package->id,
            'package_name' => $package->name,
            'package_price' => $package->price,
            'generated_date' => Carbon::now()->toDateString(),
            'due_date' => Carbon::now()->addMonthNoOverflow()->toDateString(),
            'status' => 'unpaid',
            'generated_by' => $user->id,
        ]);
        return $user;
    }
}

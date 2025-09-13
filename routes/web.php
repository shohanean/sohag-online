<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Route, Auth};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\{FrontendController, HomeController, SocialController, ProfileController, BackupController, RoleController, UserController, PackageController, CampaignController};
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\BkashController;
use App\Http\Controllers\UddoktaPayController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [FrontendController::class, 'index'])->name('index');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::post('/import', [HomeController::class, 'import'])->name('import');

//Misc. Routes
Route::get('/dollar/rate', [HomeController::class, 'dollar_rate'])->name('dollar.rate');
Route::post('/dollar/rate/insert', [HomeController::class, 'dollar_rate_insert'])->name('dollar.rate.insert');
Route::get('/active/clients', [HomeController::class, 'active_clients'])->name('active.clients');
Route::post('/change/client/info/{user}', [HomeController::class, 'change_client_info'])->name('change.client.info');
Route::get('/subscriptions', [HomeController::class, 'subscriptions'])->name('subscriptions');
Route::post('/subscription', [HomeController::class, 'subscription_store'])->name('subscription.store');
Route::get('/subscriptions/list/{user}', [HomeController::class, 'subscriptions_list'])->name('subscriptions.list');
Route::get('/subscriptions/list/{subscription}/details', [HomeController::class, 'subscriptions_list_details'])->name('subscription.list.details');
Route::put('/subscription/{subscription}', [HomeController::class, 'subscription_update'])->name('subscription.update');
Route::delete('/subscription/{subscription}', [HomeController::class, 'subscription_destroy'])->name('subscription.destroy');
Route::get('/upcoming/subscriptions', [HomeController::class, 'upcoming_subscriptions'])->name('upcoming.subscriptions');
Route::get('/upcoming/subscriptions/details/{subscription}', [HomeController::class, 'upcoming_subscriptions_details'])->name('upcoming.subscriptions.details');
Route::post('/subscription/payment/{subscription}', [HomeController::class, 'subscription_payment'])->name('subscription.payment');
Route::get('/pending/payment', [HomeController::class, 'pending_payment'])->name('pending.payment');
Route::get('/server', [HomeController::class, 'server'])->name('server.index');
Route::put('/server/{server}', [HomeController::class, 'server_update'])->name('server.update');
Route::delete('/server/{server}', [HomeController::class, 'server_destroy'])->name('server.destroy');
Route::post('/add/server', [HomeController::class, 'add_server'])->name('add.server');
Route::delete('/payment/notification/destroy/{payment_notification}', [HomeController::class, 'payment_notification_destroy'])->name('payment.notification.destroy');

//Email Verification Routes
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent again!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//Socialite Routes
Route::get('auth/facebook', [SocialController::class, 'facebookRedirect'])->name('auth.facebook');
Route::get('auth/facebook/callback', [SocialController::class, 'loginWithFacebook'])->name('auth.facebook.callback');

Route::middleware(['auth'])->group(function () {
    //Profile Routes
    Route::resource('profile', ProfileController::class);

    //Backup Routes
    Route::resource('backup', BackupController::class);

    //Role Routes
    Route::resource('role', RoleController::class);

    //User Routes
    Route::resource('user', UserController::class);

    //Package Routes
    Route::resource('package', PackageController::class);

    //Campaign Routes
    Route::post('/add/expense/{campaign}', [CampaignController::class, 'add_expense'])->name('add.expense');
    Route::post('/add/payment/{campaign}', [CampaignController::class, 'add_payment'])->name('add.payment');
    Route::post('/change/running/status/{campaign}', [CampaignController::class, 'change_running_status'])->name('change.running.status');
    Route::resource('campaign', CampaignController::class);

    //Payment Routes
    Route::get('payment/{user_id}', [PaymentController::class, 'index'])->name('payment.index');
    Route::post('payment/{client_wallet_id}', [PaymentController::class, 'store'])->name('payment.store');
    Route::put('payment/{payment}', [PaymentController::class, 'update'])->name('payment.update');
    Route::put('payment/status/change/{payment}', [PaymentController::class, 'payment_status_change'])->name('payment.status.change');
    Route::delete('payment/{payment}', [PaymentController::class, 'destroy'])->name('payment.destroy');

    //Client Routes
    Route::get('page/details/{page_id}', [ClientController::class, 'page_details'])->name('page.details');
    Route::get('campaign/details/{campaign_id}', [ClientController::class, 'campaign_details'])->name('campaign.details');
    Route::get('subscription/details/{subscription_id}', [ClientController::class, 'subscription_details'])->name('subscription.details');

    //bKash Payment Routes
    Route::post('/bkash/pay/{subscription}', [BkashController::class, 'pay'])->name('pay');

    //UddoktaPay Payment Routes
    Route::post('/checkout/{subscription}', [UddoktaPayController::class, 'checkout'])->name('uddoktapay.checkout');
    Route::get('/verify', [UddoktaPayController::class, 'verify'])->name('uddoktapay.verify');
    Route::get('/cancel', [UddoktaPayController::class, 'cancel'])->name('uddoktapay.cancel');
    Route::post('/ipn', [UddoktaPayController::class, 'ipn'])->name('uddoktapay.ipn');
    Route::post('/refund', [UddoktaPayController::class, 'refund'])->name('uddoktapay.refund');
});

Route::get('/bkash/callback', [BkashController::class, 'callback'])->name('callback');

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;

class FrontendController extends Controller
{
    function index()
    {
        $details = [
            'message' => 'This is a test email sent from Laravel 10.'
        ];

        Mail::to('shohanh1@gmail.com')->send(new TestMail($details));
        return view('index');
    }
}

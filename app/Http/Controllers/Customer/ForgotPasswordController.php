<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\ResetPassword;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;

use function Laravel\Prompts\alert;

class ForgotPasswordController extends Controller
{
    public function form()
    {
        return view('customer.password.email');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
        $customer = Customer::where('email', $request->email)->first();
        if (!$customer) {
            return alert('Email not found', 'We can\'t find a customer with that e-mail address.');
        }
        $token = Password::createToken($customer);

        DB::table('customers_password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);

        Mail::to($request->post('email'))->send(new ResetPassword($token));

        return alert('Check your email', 'We have e-mailed your password reset link!');
    }
}

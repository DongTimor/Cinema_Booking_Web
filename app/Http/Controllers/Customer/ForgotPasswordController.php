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
            return redirect()->route('customer.email.form')->with('error', 'We can not find a customer with that e-mail address.');
        }
        $email = DB::table('customers_password_reset_tokens')->where('email', $request->email)->pluck('email')->first();
        if ($email) {
            return redirect()->route('customer.email.form')->with('warning', 'You have submitted a password reset request, please check your inbox.');
        }
        $token = Password::createToken($customer);

        DB::table('customers_password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);

        Mail::to($request->post('email'))->send(new ResetPassword($token));

        return redirect()->route('customer.email.form')->with('success', 'We have e-mailed your password reset link!');
    }
}

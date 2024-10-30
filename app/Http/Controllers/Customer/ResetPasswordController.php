<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    public function form($token)
    {
        return view('customer.password.confirm', compact('token'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $email = DB::table('customers_password_reset_tokens')->where('token', $request->token)->pluck('email')->first();
        $customer = Customer::where('email', $email)->first();
        $customer->password = Hash::make($request->password);
        $customer->save();

        DB::table('customers_password_reset_tokens')->where('email', $email)->delete();
        return redirect()->route('customer.login.form')->with('success', 'Your password has been reset.');
    }
}

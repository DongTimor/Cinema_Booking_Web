<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function form()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
        $customer = $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($customer['password']),
        ]);

        return redirect()->route('customer.login');
    }
}

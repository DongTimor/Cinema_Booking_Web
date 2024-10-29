<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends Controller
{
    public function form()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!auth('customer')->attempt($credentials)) {
                abort(401);
            }

            $customer = auth('customer')->user();

            $customClaims = [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'image' => $customer->image,
            ];

            $token = JWTAuth::claims($customClaims)->fromUser($customer);
            $cookie = cookie('token', $token, 60 * 24 * 30);
            return redirect('/home')->withCookie($cookie);
        } catch (JWTException $e) {
            return redirect()->route('customer.login'); ;
        }
    }

    public function logout()
    {
        auth('customer')->logout();
        return redirect()->route('customer.login');
    }
}

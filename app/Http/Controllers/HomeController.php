<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $token = $request->cookie('token');
        $customer = JWTAuth::setToken($token)->getPayload();
        return view('home', compact('customer'));
    }

    public function getMovies()
    {
        $movies = Movie::find(30);
        return response()->json($movies);
    }
}

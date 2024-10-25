<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Point;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ranking = Point::where('user_id', auth()->id())->value('ranking_level');
        $movies = Movie::with('images')->get(); 
        return view('welcome',compact('movies','ranking'));
    }

    public function detail($id)
    {
        $user = Auth::user();
        $userVouchers = $user->vouchers->where('pivot.status', 0)->pluck('pivot.voucher_id');
        $vouchers = Voucher::all();
        $movie = Movie::findOrFail($id);
        return view('customer.movie-detail',compact('movie','userVouchers','vouchers'));
    }
}

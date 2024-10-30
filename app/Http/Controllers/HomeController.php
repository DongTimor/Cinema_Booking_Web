<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Point;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('home');
    }

    public function getMovies()
    {
        $movies = Movie::find(30);
        return response()->json($movies);
    }
}



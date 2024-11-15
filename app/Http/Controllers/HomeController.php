<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Point;
use App\Models\Schedule;
use App\Models\Seat;
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
    public function __construct() {}

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $ranking = 'Bronze';
        $movies = Movie::with('images')->get();
        $customer = auth('customer')->user();
        if ($customer) {
            $ranking = Point::where('customer_id', $customer->id)->value('ranking_level');
        }
        return view('home', compact('customer', 'movies', 'ranking'));
    }
}

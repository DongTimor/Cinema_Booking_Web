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
        return view('welcome', compact('movies', 'ranking'));
    }

    public function detail($id)
    {
        $user = Auth::user();
        $userVouchers = $user->vouchers->where('pivot.status', 0)->pluck('pivot.voucher_id');
        $vouchers = Voucher::all();
        $movie = Movie::findOrFail($id);
        $today = Carbon::today()->format('Y-m-d');
        $schedule = Schedule::with('showtimes')
            ->where('movie_id', $id)
            ->whereDate('date', $today)
            ->first();
        $showtimes = $schedule->showtimes;
        
        // $seats = Seat::All();
        return view('customer.movie-detail', compact('movie', 'userVouchers', 'vouchers', 'showtimes', 'today'));
    }
    public function getTimeslotsByDate(Request $request)
    {
        $date = $request->query('date');
        $movieId = $request->query('movie_id');
        $showtimes = Schedule::where('movie_id', $movieId)
            ->whereDate('date', $date)
            ->with('showtimes')
            ->get()
            ->pluck('showtimes')
            ->flatten();
        return response()->json($showtimes);
    }

    public function getSeatsByShowtimeAndAuditorium(Request $request)
    {
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $showtimeId = $request->input('showtime_id');
    
        $auditoriumId = Schedule::with('showtimes')
        ->whereHas('showtimes', function($query) use ($showtimeId) {
            $query->where('showtime_id', $showtimeId);
        })
        ->whereDate('date', $date)
        ->where('movie_id', $movieId)
        ->value('auditorium_id');
    
        $seats = Seat::where('auditorium_id', $auditoriumId)->get();
        return response()->json($seats);
    }
}

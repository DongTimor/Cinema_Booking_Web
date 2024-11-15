<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $movies = Movie::with('images')->get();
        $customer = auth('customer')->user();
        return view('home.movies.index', compact('customer', 'movies'));
    }

    public function detail($id)
    {
        $customer = auth('customer')->user();
        $vouchers = $customer->vouchers()
            ->where('expires_at', '>=', now()->format('Y-m-d'))
            ->where('quantity', '>', 0)
            ->wherePivot('status', 0)
            ->get()
            ->groupBy('type')
            ->flatten()
            ->sortBy('value');
        $movie = Movie::findOrFail($id);
        $today = Carbon::today();
        $dates = collect(range(0, 6))->map(fn(int $day) => $today->copy()->addDays($day));
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $id)
            ->whereDate('date', $today)
            ->get();
        $showtimes = $schedules->pluck('showtimes')->flatten()->sortBy('start_time');
        return view('home.movies.detail', compact('movie', 'vouchers', 'showtimes', 'today', 'dates', 'customer'));
    }

    public function getShowtimes(Request $request)
    {
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $movieId)
            ->whereDate('date', $date)
            ->get();
        $showtimes = $schedules->pluck('showtimes')->flatten()->sortBy('start_time');
        return view('home.movies.showtimes', compact('showtimes', 'date', 'movieId'));
    }

    public function getSeats(Request $request)
    {
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $showtimeId = $request->input('showtime_id');
        $price = $request->input('price');
        $schedule = Schedule::with('showtimes')
            ->whereDate('date', $date)
            ->where('movie_id', $movieId)
            ->whereRelation('showtimes', 'showtime_id', $showtimeId)
            ->first();
        $auditoriumId = $schedule->auditorium_id;
        $seats = Seat::where('auditorium_id', $auditoriumId)->get();
        $rows = $seats->groupBy('row')->count();
        $orderedSeats = Ticket::where('movie_id', $movieId)
            ->where('schedule_id', $schedule->id)
            ->where('showtime_id', $showtimeId)
            ->pluck('seat_id')
            ->toArray();
        return view('home.movies.seats', compact('seats', 'rows', 'orderedSeats'));
    }
}

<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
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
        $vouchers = $customer->vouchers
        ->where('expires_at', '>=', now()->format('Y-m-d'))
        ->where('quantity', '>', 0);
        $movie = Movie::findOrFail($id);
        $today = Carbon::today()->format('Y-m-d');
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $id)
            ->whereDate('date', $today)
            ->get();
        $showtimes = $schedules->pluck('showtimes')->flatten();
        return view('home.movies.detail', compact('movie', 'vouchers', 'showtimes', 'today', 'customer', 'schedules'));
    }

    public function getTimeslotsByDate(Request $request)
    {
        $date = $request->query('date');
        $movieId = $request->query('movie_id');
        $scheduleId = Schedule::where('movie_id', $movieId)->whereDate('date', $date)->value('id');
        $showtimes = Schedule::where('movie_id', $movieId)
            ->whereDate('date', $date)
            ->with('showtimes')
            ->get()
            ->pluck('showtimes')
            ->flatten();
        return response()->json([
            'showtimes' => $showtimes,
            'scheduleId' => $scheduleId
        ]);
    }

    public function getSeats(Request $request)
    {
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $showtimeId = $request->input('showtime_id');
        $schedule = Schedule::with('showtimes')
            ->whereHas('showtimes', function ($query) use ($showtimeId) {
                $query->where('showtime_id', $showtimeId);
            })
            ->whereDate('date', $date)
            ->where('movie_id', $movieId)
            ->first();
        if (!$schedule) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }
        $auditoriumId = $schedule->auditorium_id;
        $price = Movie::where('id', $movieId)->value('price');
        $seats = Seat::where('auditorium_id', $auditoriumId)
            ->with(['tickets' => function ($query) use ($showtimeId, $schedule) {
                $query->where('showtime_id', $showtimeId)
                    ->where('status', 'ordered')
                    ->where('schedule_id', $schedule->id);
            }])
            ->get();
        return response()->json(['seats' => $seats, 'price' => $price]);
    }
}

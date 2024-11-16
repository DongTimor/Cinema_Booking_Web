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
        
        $showtimes = $schedules->pluck('showtimes')->flatten()->unique('id')->sortBy('start_time');
        foreach ($showtimes as $showtime) {
            $showtime_schedules = $schedules->filter(function($schedule) use ($showtime) {
                return $schedule->showtimes->contains('id', $showtime->id);
            });
            $totalOrderedSeats = Ticket::where('movie_id', $movieId)
                ->where('showtime_id', $showtime->id)
                ->whereIn('schedule_id', $showtime_schedules->pluck('id'))
                ->count();
            $totalSeats = Seat::whereIn('auditorium_id', $showtime_schedules->pluck('auditorium_id'))->count();
            $showtime->is_full = $totalOrderedSeats == $totalSeats;
            $showtimeDateTime = Carbon::parse($showtime_schedules->first()->date . ' ' . $showtime->start_time);
            $showtime->is_past = $showtimeDateTime->isPast();
        }
        return view('home.movies.showtimes', compact('showtimes', 'date', 'movieId'));
    }

    public function getSeats(Request $request)
    {
        $date = $request->input('date');
        $movieId = $request->input('movie_id');
        $showtimeId = $request->input('showtime_id');
        $price = $request->input('price');
        $schedules = Schedule::with('showtimes')
            ->whereDate('date', $date)
            ->where('movie_id', $movieId)
            ->whereRelation('showtimes', 'showtime_id', $showtimeId)
            ->get();

        $auditorium = null;
        foreach ($schedules as $schedule) {
            $auditoriumId = $schedule->auditorium_id;
            $seats = Seat::where('auditorium_id', $auditoriumId)->get();
            $rows = $seats->groupBy('row')->count();
            $orderedSeats = Ticket::where('movie_id', $movieId)
                ->where('auditorium_id', $auditoriumId)
                ->where('schedule_id', $schedule->id)
                ->where('showtime_id', $showtimeId)
                ->pluck('seat_id')
                ->toArray();
            if (count($orderedSeats) < $seats->count()) {
                $auditorium = [
                    'seats' => $seats,
                    'rows' => $rows,
                    'orderedSeats' => $orderedSeats,
                    'auditoriumId' => $auditoriumId,
                ];
                break;
            }
        }
        if (is_null($auditorium)) {
            return back()->with('error', 'Currently, there are no available seats for this showtime. Please try again later or choose another showtime!');
        }
        return view('home.movies.seats', compact('auditorium'));
    }
}
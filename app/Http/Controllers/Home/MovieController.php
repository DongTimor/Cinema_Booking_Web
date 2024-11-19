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
        $customer = auth('customer')->user();
        $movies = Movie::with('images')
            ->where('status', 'active')
            ->whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();
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
        $dates = collect(range(0, 6))->map(fn(int $day) => today()->addDays($day));
        $showtimes = $this->handleShowtimes($id, today())
            ->filter(function ($showtime) {
                return $showtime->start_time >= now()->format('H:i');
            });

        return view('home.movies.detail', compact('movie', 'vouchers', 'showtimes', 'dates', 'customer'));
    }

    public function getShowtimes(Request $request)
    {
        $date = $request->input('date');
        $movie_id = $request->input('movie_id');
        $showtimes = $this->handleShowtimes($movie_id, $date);
        return view('home.movies.showtimes', compact('showtimes', 'date', 'movie_id'));
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

        foreach ($schedules as $schedule) {
            $schedule_id = $schedule->id;
            $auditorium_id = $schedule->auditorium_id;
            $auditorium_name = $schedule->auditorium->name;
            $seats = Seat::where('auditorium_id', $auditorium_id)->get();
            $rows = $seats->groupBy('row')->count();

            $orderedSeats = Ticket::where('movie_id', $movieId)
                ->where('auditorium_id', $auditorium_id)
                ->where('schedule_id', $schedule_id)
                ->where('showtime_id', $showtimeId)
                ->pluck('seat_id')
                ->toArray();

            if (count($orderedSeats) < $seats->count()) {
                return view('home.movies.seats', compact('seats', 'rows', 'orderedSeats', 'schedule_id', 'auditorium_id', 'auditorium_name'));
            }
        }
    }

    public function handleShowtimes($movie_id, $date)
    {
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $movie_id)
            ->whereDate('date', $date)
            ->get();
        $currentDate = now()->toDateString();
        $currentTime = now()->format('H:i');
        $showtimes = $schedules->flatMap(fn($schedule) => $schedule->showtimes)
            ->filter(function ($showtime) use ($date, $currentDate, $currentTime) {
                if ($date === $currentDate) {
                    return $showtime->start_time >= $currentTime;
                }
                return true;
            })
            ->unique('id')
            ->sortBy('start_time');

        $scheduleIds = $schedules->pluck('id');
        $auditoriumIds = $schedules->pluck('auditorium_id');

        $tickets = Ticket::where('movie_id', $movie_id)
            ->whereIn('auditorium_id', $auditoriumIds)
            ->whereIn('schedule_id', $scheduleIds)
            ->get()
            ->groupBy('showtime_id')
            ->map(fn($group) => $group->count());

        $seats = Seat::whereIn('auditorium_id', $auditoriumIds)
            ->get()
            ->groupBy('auditorium_id')
            ->map(fn($group) => $group->count());
        $showtimes = $showtimes->map(function ($showtime) use ($tickets, $seats, $schedules) {
            $orderedCount = $tickets->get($showtime->id, 0);
            $totalSeats = $schedules->filter(
                fn($schedule) =>
                $schedule->showtimes->contains('id', $showtime->id)
            )->pluck('auditorium_id')->sum(fn($auditoriumId) => $seats->get($auditoriumId, 0));
            $showtime->count = $orderedCount;
            $showtime->seats = $totalSeats;

            return $showtime;
        });

        return $showtimes;
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Movie;
use App\Models\Point;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\Voucher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

    public function detail($id)
    {
        $customer = auth('customer')->user();
        $customerVouchers = $customer->vouchers->where('pivot.status', 0)->pluck('pivot.voucher_id');
        $vouchers = Voucher::all();
        $movie = Movie::findOrFail($id);
        $today = Carbon::today()->format('Y-m-d');
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $id)
            ->whereDate('date', $today)
            ->get();
        $showtimes = $schedules->pluck('showtimes')->flatten();
        return view('customer.movie-detail', compact('movie', 'customerVouchers', 'vouchers', 'showtimes', 'today', 'customer', 'schedules'));
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

    public function favoriteMovie()
    {
        $oneWeekAgo = now()->subWeek();

        $favoriteMovies = Ticket::where('created_at', '>=', $oneWeekAgo)
            ->select('movie_id', DB::raw('count(*) as total_tickets'))
            ->groupBy('movie_id')
            ->orderBy('total_tickets', 'desc')
            ->with('movie')
            ->take(20)
            ->get();
        if ($favoriteMovies->isEmpty()) {
            return response()->json(['message' => 'There were no popular movies last week.']);
        }
        return response()->json([
            'favorite_movies' => $favoriteMovies->map(function ($movie) {
                return [
                    'movie_id' => $movie->movie->id,
                    'movie_name' => $movie->movie->name,
                    'total_tickets' => $movie->total_tickets
                ];
            })
        ]);
    }

    public function todayEvent()
    {
        $today = Carbon::today();
        $events = Event::whereDate('start_date', '<=', $today)
                    ->whereDate('end_date', '>=', $today)
                    ->get();
        return response()->json([
            'events' => $events->map(function ($event) {
                return [
                    'event_id' => $event->id,
                    'title' => $event->title,
                    'description' => $event->description,
                    'all_day' => $event->all_day,
                    'discount_percentage' => $event->discount_percentage,
                    'number_of_tickets' => $event->number_of_tickets,
                    'start_date' => $event->start_date,
                    'end_date' => $event->end_date,
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'movies' => $event->movies->map(function ($movie) {
                        return [
                            'id' => $movie->id,
                            'name' => $movie->name,
                            'price' => $movie->price,
                            'start_date' => $movie->start_date,
                            'end_date' => $movie->end_date,
                        ];
                    }),
                ];
            })
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Movie;
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

    public function homepage()
    {
        $today = Carbon::today();
        $events = Event::whereDate('start_date', '<=', $today)
                        ->whereDate('end_date', '>=', $today)
                        ->get();

        $oneWeekAgo = now()->subWeek();

        $favoriteMovies = Ticket::where('tickets.created_at', '<=', $oneWeekAgo)
            ->join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select('movies.id as movie_id', 'movies.name as movie_name', DB::raw('count(tickets.id) as total_tickets'))
            ->groupBy('movies.id', 'movies.name')
            ->orderBy('total_tickets', 'desc')
            ->take(20)
            ->get();

            if ($favoriteMovies === null) {
                return view('home', ['message' => 'There were no popular movies last week.']);
            }

        $customer = auth('customer')->user();
        $customerVouchers = $customer->vouchers->pluck('pivot.voucher_id');
        $vouchers = Voucher::whereDate('expires_at', '=', Carbon::now())->get();

        return view('home', [
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
                            'image_url' => $movie->images()->first()->url ?? asset('default.jpg'),
                        ];
                    }),
                ];
            }),
            'favoriteMovies' => $favoriteMovies,
            'customerVouchers' => $customerVouchers,
            'vouchers' => $vouchers,
            'customer' => $customer
        ]);
    }

    public function favoriteMovieAll()
    {
        $customer = auth('customer')->user();
        $favoriteMovies = Ticket::join('movies', 'tickets.movie_id', '=', 'movies.id')
            ->select('movies.id as movie_id', 'movies.name as movie_name', 'movies.price' , DB::raw('count(tickets.id) as total_tickets'))
            ->groupBy('movies.id', 'movies.name', 'movies.price')
            ->orderBy('total_tickets', 'desc')
            ->take(20)
            ->get();

        return view('today.favorite_movies', [
            'favoriteMovies' => $favoriteMovies,
            'customer' => $customer
        ]);
    }

    public function voucherNowAll(Request $request)
    {
        $customer = auth('customer')->user();
        $today = Carbon::today();

        if ($request->has('voucher_id')) {
            $voucherId = $request->input('voucher_id');
            $voucher = Voucher::find($voucherId);

            if ($voucher && $voucher->quantity > 0) {
                $voucher->quantity -= 1;
                $voucher->save();

                $voucher->customers()->attach($customer->id, ['voucher_id' => $voucherId]);

                return redirect()->route('vouchers-now')->with('success', 'Voucher saved successfully.');
            }

            return redirect()->route('vouchers-now')->with('error', 'Voucher could not be saved.');
        }

        $vouchers = Voucher::whereDate('expires_at', '=', $today)->get();

        $customerVouchers = $customer->vouchers->pluck('id');

        return view('today.vouchers', [
            'vouchers' => $vouchers,
            'customer' => $customer,
            'customerVouchers' => $customerVouchers,
        ]);
    }

    public function discountMovieAll()
    {
        $customer = auth('customer')->user();
        $today = Carbon::today();

        $events = Event::whereDate('start_date', '<=', $today)
                       ->whereDate('end_date', '>=', $today)
                       ->get();

        $discountMovies = $events->map(function ($event) {
            return $event->movies->map(function ($movie) use ($event) {
                return [
                    'id' => $movie->id,
                    'name' => $movie->name,
                    'price' => $movie->price,
                    'start_date' => $movie->start_date,
                    'end_date' => $movie->end_date,
                    'image_url' => $movie->images()->first()->url ?? asset('default.jpg'),
                    'discount_percentage' => $event->discount_percentage,
                    'discounted_price' => $movie->price - ($movie->price * ($event->discount_percentage / 100)),
                ];
            });
        })->flatten(1);
        return view('today.discount_movies', [
            'discountMovies' => $discountMovies,
            'customer' => $customer
        ]);
    }

    public function eventNowAll()
    {
        $customer = auth('customer')->user();
        $today = Carbon::today();

        $events = Event::whereDate('start_date', '<=', $today)
                       ->whereDate('end_date', '>=', $today)
                       ->get();

        return view('today.events', [
            'events' => $events,
            'customer' => $customer
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TicketRequest;
use App\Mail\TicketConfirmation;
use App\Models\Customer;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Voucher;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('customer', 'user')->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movies = Movie::whereDate('start_date', '<=', today())
            ->whereDate('end_date', '>=', today())
            ->get();
        $vouchers = Voucher::whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->orderBy('type')
            ->orderByDesc('value')
            ->get()
            ->groupBy('type')
            ->flatten();
        return view('admin.tickets.create', compact('movies', 'vouchers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'movie_id' => 'required',
            'showtime_id' => 'required',
        ]);

        $movie_id = $request->input('movie_id');
        $orderData = json_decode(base64_decode($request->input('order_id')), true);

        $schedule_id = Schedule::where('movie_id', $movie_id)
            ->where('auditorium_id', $request->input('auditorium_id'))
            ->whereDate('date', today())
            ->value('id');

        $customer_id = $request->input('customer_id');
        $customer = Customer::find($customer_id);

        if (isset($jsonData['voucherId'])) {
            $customer->vouchers()->updateExistingPivot($jsonData['voucherId'], ['status' => 1]);
        }

        $ticketData = [
            'user_id' => auth()->id(),
            'customer_id' => $customer_id,
            'movie_id' => $movie_id,
            'showtime_id' => $request->input('showtime_id'),
            'schedule_id' => $schedule_id,
            'price' => $orderData['price'] / count($orderData['seats']),
        ];

        $tickets = array_map(function ($seat) use ($ticketData) {
            return array_merge($ticketData, ['seat_id' => $seat['seatId']]);
        }, $orderData['seats']);

        Ticket::insert($tickets);

        if ($customer->email) {
            $movie = Movie::find($movie_id);
            $orderData['movie'] = $movie->name;
            $orderData['customer'] = $customer->name;
            Mail::to($customer->email)->send(new TicketConfirmation($customer, $orderData));
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        $movies = Movie::where('end_date', '>=', Carbon::now())
            ->get();
        $users = User::all();
        $customers = Customer::all();
        $vouchers = Voucher::all();
        $seats = Seat::whereBelongsTo($ticket->schedule->auditorium)
            ->get();
        return view('admin.tickets.edit', compact('ticket', 'users', 'customers', 'movies', 'seats', 'vouchers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TicketRequest $request, Ticket $ticket)
    {
        try {
            $ticket->update($request->all());
            return response()->json(['message' => 'Cập nhật vé thành công', 'ticket' => $ticket]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        try {
            $ticket->delete();
            return response()->json(['message' => 'Ticket deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getTicketsOfSchedule($movie, $date, $auditorium, $showtime)
    {
        $schedule = Schedule::where('movie_id', $movie)
            ->whereDate('date', $date)
            ->where('auditorium_id', $auditorium)
            ->first();
        if (!$schedule) {
            return response()->json(['error' => 'Schedule not found'], 404);
        }
        $tickets = Ticket::where('schedule_id', $schedule->id)
            ->where('showtime_id', $showtime) // Replace with the correct column name
            ->get()
            ->pluck('seat_id');
        if ($tickets->count() > 0) {
            return $tickets;
        } else {
            return null;
        }
    }

    public function ticketConfirmationPdf($data)
    {
        $data = json_decode(base64_decode($data), true);
        $pdf = PDF::loadView('pdfs.ticket-confirmation', compact('data'));
        return $pdf->download('ticket-confirmation.pdf');
    }

    public function fetchCustomer($phone)
    {
        $customer = Customer::where('phone_number', $phone)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found!'], 404);
        }

        return view('admin.tickets.customer', compact('customer'));
    }

    public function fetchShowtimes($movie_id, $date)
    {
        $schedules = Schedule::with('showtimes')
            ->where('movie_id', $movie_id)
            ->whereDate('date', $date)
            ->get();
        $showtimes = $schedules->flatMap(fn($schedule) => $schedule->showtimes)
            ->where('start_time', '>=', now()->format('H:i'))
            ->unique('id')
            ->sortBy('start_time');
        return view('admin.tickets.showtimes', compact('showtimes'));
    }

    public function fetchAuditoriums($movie_id, $date, $showtime_id)
    {
        $schedules = Schedule::with('auditorium')
            ->whereDate('date', $date)
            ->where('movie_id', $movie_id)
            ->whereRelation('showtimes', 'showtime_id', $showtime_id)
            ->get();
        $auditoriums = $schedules->pluck('auditorium');
        return view('admin.tickets.auditoriums', compact('auditoriums'));
    }

    public function fetchSeats($movie_id, $date, $showtime_id, $auditorium_id)
    {
        $schedule = Schedule::whereDate('date', $date)
            ->where('movie_id', $movie_id)
            ->whereRelation('showtimes', 'showtime_id', $showtime_id)
            ->first();
        $orderedSeats = Ticket::where('movie_id', $movie_id)
            ->where('schedule_id', $schedule->id)
            ->where('showtime_id', $showtime_id)
            ->pluck('seat_id')
            ->toArray();
        $seats = Seat::where('auditorium_id', $auditorium_id)->get();
        $rows = $seats->groupBy('row')->count();
        return view('admin.tickets.seats', compact('orderedSeats', 'seats', 'rows'));
    }

    public function fetchVouchers($customer_id)
    {
        $customer = Customer::find($customer_id);
        $vouchers = $customer->vouchers()
            ->whereDate('expires_at', '>=', today())
            ->where('quantity', '>', 0)
            ->wherePivot('status', 0)
            ->get()
            ->groupBy('type')
            ->flatten()
            ->sortBy('value');

        return view('admin.tickets.vouchers', compact('vouchers'));
    }
}

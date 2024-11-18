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
use Illuminate\Support\Facades\Log;
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
        $movies = Movie::whereDate('end_date', '>=', Carbon::now())
            ->get();
        $vouchers = Voucher::whereDate('expires_at', '>=', Carbon::now())
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
        try {
            $request->validate([
                'movie_id' => 'required',
                'showtime_id' => 'required',
                'seats' => 'required',
            ]);

            $seats = $request->input('seats');
            $tickets = $request->except('seats');
            $schedule_id = Schedule::where('movie_id', $request->movie_id)
                ->where('auditorium_id', $request->auditorium_id)
                ->whereDate('date', Carbon::now())
                ->value('id');
            foreach ($seats as $seat) {
                $tickets['user_id'] = auth()->user()->id;
                $tickets['seat_id'] = $seat;
                $tickets['status'] = 'ordered';
                $tickets['schedule_id'] = $schedule_id;
                Ticket::create($tickets);
            }

            $voucher = Voucher::find($request->voucher_id);
            if ($voucher) {
                $voucher->quantity -= 1;
                $voucher->save();
            }

            if ($request->voucher_id !== null) {
                $customer = Customer::find($request->customer_id);
                if ($customer && $customer->vouchers()->where('voucher_id', $request->voucher_id)->exists()) {
                    $customer->vouchers()->updateExistingPivot($request->voucher_id, ['status' => '1']);
                }
            }

            return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
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

    public function ticketConfirmationMail(Request $request)
    {
        try {
            if ($request->customer_email) {
                Mail::to($request->customer_email)->send(new TicketConfirmation($request));
                return response()->json(['message' => 'Mail sent successfully']);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function ticketConfirmationPdf(Request $request)
    {
        $data = [
            'title' => 'Ticket Confirmation',
            'data' => $request->all()
        ];
        $pdf = PDF::loadView('pdfs.ticket-confirmation', compact('data'));
        return $pdf->download('ticket-confirmation.pdf');
    }

    public function search($phone)
    {
        $customer = Customer::where('phone_number', $phone)->first();

        if (!$customer) {
            return response()->json(['message' => 'Customer not found!'], 404);
        }

        return view('admin.tickets.customer', compact('customer'));
    }

    public function getVoucherList($customer_id)
    {
        $customer = Customer::find($customer_id);
        $vouchers = $customer
            ->vouchers()
            ->wherePivot('status', '0')
            ->get();

        return view('admin.tickets.voucher-list', compact('vouchers'));

    }
}

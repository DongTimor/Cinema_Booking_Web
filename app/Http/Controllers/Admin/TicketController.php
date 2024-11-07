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
use Illuminate\Support\Facades\Log;


class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('customer','user')->get();
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movies = Movie::whereDate('end_date', '>=', Carbon::now())
            ->get();
        $customers = Customer::all();
        $vouchers = Voucher::all();
        return view('admin.tickets.create', compact('movies', 'customers', 'vouchers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketRequest $request)
    {
        try {
            Ticket::create($request->all());
            if ($request->voucher_id !== null) {
                $customer = Customer::find($request->customer_id);
                if ($customer && $customer->vouchers()->where('voucher_id', $request->voucher_id)->exists()) {
                    $customer->vouchers()->updateExistingPivot($request->voucher_id, ['status' => '1']);
                }
            }
            return response()->json([
                'message' => "Customer: {$request->customer_id} - Seat: {$request->seat_id} - {$request->status} : Ticket created successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
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
            ->get();
        return response()->json($tickets);
    }

    public function ticketConfirmationMail(TicketRequest $request)
    {
        try {
            Mail::to($request->customer_email)->send(new TicketConfirmation($request));
            return response()->json(['message' => 'Mail sent successfully']);
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
}

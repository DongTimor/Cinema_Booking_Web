<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Movie;
use App\Models\Seat;
use App\Models\Showtime;
use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tickets = Ticket::with('customer', 'showtime.movie', 'showtime.auditorium', 'seat', 'user')->get();
        return view('ticket.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Ticket $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ticket $ticket)
    {
        // $ticket = Ticket::with('customer', 'showtime.movie', 'showtime.auditorium', 'seat', 'user')->find($ticket);
        $movies = Movie::where('end_time', '>=', Carbon::now())
        ->get();
        $users = User::all();
        $customers = Customer::all();
        $showtimes = $ticket->showtime->movie->showtimes
            ->where('end_time', '>=', Carbon::now()->addMinutes($ticket->showtime->movie->duration));
        $seats = Seat::whereBelongsTo($ticket->showtime->auditorium)
            ->get();
        return view('ticket.edit', compact('ticket', 'movies', 'users', 'customers', 'showtimes', 'seats'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ticket $ticket)
    {
        return redirect()->route('tickets.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ticket $ticket)
    {
        //
    }
}

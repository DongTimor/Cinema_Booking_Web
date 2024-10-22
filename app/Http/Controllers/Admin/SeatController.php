<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditorium;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\CollectsArbitraryKeys;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $seats = Seat::all();
        return view('admin.seats.index', compact('seats',));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $auditoriums = Auditorium::all();
        return view('admin.seats.create', compact('auditoriums'));
    }

    public function singleCreate()
    {
        $auditoriums = Auditorium::all();
        return view('admin.seats.single-create', compact('auditoriums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $auditorium = Auditorium::findOrFail($request->auditorium_id);
            $totalSeats = $auditorium->total;
            $totalAvailableSeats = $auditorium->seats()->count();
            if ($totalAvailableSeats >= $totalSeats) {
                return response()->json(['message' => 'Auditorium is full']);
            }
            $validated = $request->validate([
                'auditorium_id' => 'required|exists:auditoriums,id',
                'seat_number' => 'required|string|max:10',
                'row' => 'required|integer',
                'column' => 'required|integer',
            ]);
            Seat::create($validated);
            return response()->json(['message' => 'Seat created successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Seat $seat)
    {
        $schedules = $seat->auditorium->schedules()->whereDate('date', '>=', now()->toDateString())->get();
        $informations = $schedules->flatMap(function ($schedule) {
            return collect($schedule->showtimes)->map(function ($showtime) use ($schedule) {
                $info = new \stdClass();
                $info->showtime = $showtime;
                $info->date = $schedule->date;
                $info->movie = $schedule->movie->name;
                return $info;
            });
        })->all();
        return view('admin.seats.show', compact('seat', 'informations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Seat $seat)
    {
        return view('admin.seats.edit', compact('seat'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Seat $seat)
    {
        $validated = $request->validate([
            'seat_number' => 'required|string|max:10',
        ]);
        $seat->update($validated);
        return redirect()->route('seats.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Seat $seat)
    {
        try {
            $seat->delete();
            return redirect()->route('seats.index')->with('success', 'Seat deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('seats.index')->with('error', $e->getMessage());
        }
    }

    public function getSeatsOfAuditorium($auditorium)
    {
        $seats = Seat::where('auditorium_id', $auditorium)->get();
        return response()->json($seats);
    }
}

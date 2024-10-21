<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditorium;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\Showtime;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ShowtimeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $showtimes = Showtime::all();
        return view('admin.showtimes.index', compact('showtimes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.showtimes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        Showtime::create($validated);
        return redirect()->route('showtimes.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Showtime $showtime)
    {
        return view('admin.showtimes.edit', compact('showtime'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Showtime $showtime)
    {
        $validated = $request->validate([
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
        ]);
        $showtime->update($validated);
        return redirect()->route('showtimes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Showtime $showtime)
    {
        $showtime->delete();
        return redirect()->route('showtimes.index');
    }

    public function getSeats(string $id)
    {
        $showtime = Showtime::find($id);
        $seats = Seat::whereBelongsTo($showtime->auditorium)
            ->whereDoesntHave('ticket')
            ->get();
        return response()->json($seats);
    }

    public function getShowtimesOfDuration($duration)
    {
        $showtimes = Showtime::whereRaw('TIMESTAMPDIFF(MINUTE, start_time, end_time) > ?', [$duration])->get();
        return response()->json($showtimes);
    }

    public function getDullicateShowtimes($auditoriums, $date)
    {
        $showtimes = Schedule::whereDate('date', $date)
            ->where('auditorium_id', $auditoriums)
            ->whereHas('showtimes')
            ->with(['showtimes'])
            ->get()
            ->flatMap(function ($schedule) {
                return $schedule->showtimes->map(function ($showtime) use ($schedule) {
                    $showtime->schedule_id = $schedule->id;
                    $showtime->movie_id = $schedule->movie_id;
                    return $showtime;
                });
            })
            ->unique('id');

        return response()->json($showtimes);
    }

    public function getAvailableShowtimes($auditoriums, $date, $duration)
    {
        $duplicateShowtimes = Schedule::whereDate('date', $date)
            ->where('auditorium_id', $auditoriums)
            ->whereHas('showtimes')
            ->with(['showtimes'])
            ->get()
            ->pluck('showtimes')
            ->flatten()
            ->unique('id');

        $showtimes = Showtime::whereNotIn('id', $duplicateShowtimes->pluck('id'))
            ->where(function ($query) use ($duplicateShowtimes) {
                foreach ($duplicateShowtimes as $duplicate) {
                    $query->where(function ($subQuery) use ($duplicate) {
                        if (Carbon::parse($duplicate->start_time)->lte(Carbon::parse('00:15'))) {
                            $subQuery->where('start_time', '>=', Carbon::parse($duplicate->end_time)->addMinutes(15));
                        } elseif (Carbon::parse($duplicate->end_time)->gte(Carbon::parse('23:45'))) {
                            $subQuery->where('end_time', '<=', Carbon::parse($duplicate->start_time)->subMinutes(15));
                        } else {
                            $subQuery->where(function ($innerQuery) use ($duplicate) {
                                $innerQuery->where('start_time', '>=', Carbon::parse($duplicate->end_time)->addMinutes(15))
                                    ->orWhere('end_time', '<=', Carbon::parse($duplicate->start_time)->subMinutes(15));
                            });
                        }
                    });
                }
            })
            ->whereRaw('TIMESTAMPDIFF(MINUTE, start_time, end_time) > ?', [$duration])
            ->get();

        return response()->json($showtimes);
    }

    public function getAvailableShowtimesOfSchedule($schedule, $auditoriums, $date, $duration)
    {
        $schedule = (int) $schedule;
        $duplicateShowtimes = Schedule::whereDate('date', $date)
            ->where('auditorium_id', $auditoriums)
            ->whereHas('showtimes')
            ->with(['showtimes'])
            ->get()
            ->pluck('showtimes')
            ->flatten()
            ->filter(function ($showtime) use ($schedule) {
                return $showtime->pivot->schedule_id !== $schedule;
            })
            ->unique('id');

        $showtimes = Showtime::whereNotIn('id', $duplicateShowtimes->pluck('id'))
            ->where(function ($query) use ($duplicateShowtimes) {
                foreach ($duplicateShowtimes as $duplicate) {
                    $query->where(function ($subQuery) use ($duplicate) {
                        if (Carbon::parse($duplicate->start_time)->lte(Carbon::parse('00:15'))) {
                            $subQuery->where('start_time', '>=', Carbon::parse($duplicate->end_time)->addMinutes(15));
                        } elseif (Carbon::parse($duplicate->end_time)->gte(Carbon::parse('23:45'))) {
                            $subQuery->where('end_time', '<=', Carbon::parse($duplicate->start_time)->subMinutes(15));
                        } else {
                            $subQuery->where(function ($innerQuery) use ($duplicate) {
                                $innerQuery->where('start_time', '>=', Carbon::parse($duplicate->end_time)->addMinutes(15))
                                    ->orWhere('end_time', '<=', Carbon::parse($duplicate->start_time)->subMinutes(15));
                            });
                        }
                    });
                }
            })
            ->whereRaw('TIMESTAMPDIFF(MINUTE, start_time, end_time) > ?', [$duration])
            ->get();
        return response()->json($showtimes);
    }

    public function getShowtimesOfAuditorium($auditorium)
    {
        $showtimes = Schedule::where('auditorium_id', $auditorium)
            ->whereHas('showtimes')
            ->with(['showtimes'])
            ->get()
            ->flatMap(function ($schedule) {
                return $schedule->showtimes->map(function ($showtime) use ($schedule) {
                    $showtime->schedule_id = $schedule->id;
                    $showtime->movie_id = $schedule->movie_id;
                    $showtime->movie_title = $schedule->movie->name;
                    $showtime->date = $schedule->date;
                    $showtime->auditorium_id = $schedule->auditorium_id;
                    return $showtime;
                });
            });

        return response()->json($showtimes);
    }
}

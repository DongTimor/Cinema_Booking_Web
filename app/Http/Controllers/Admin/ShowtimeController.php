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

        return $showtimes;
    }

    public function withAuditorium($auditorium)
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

        return $showtimes;
    }

    public function withMovieAndDate($date, $movie)
    {
        $schedules = Schedule::whereDate('date', $date)
            ->where('movie_id', $movie)
            ->first();

        $showtimes = $schedules
            ->showtimes()
            ->get()
            ->filter(fn($showtime) => Carbon::parse($showtime->start_time)->gt(Carbon::now()))
            ->values();

        return $showtimes;
    }

    public function withSchedule($schedule)
    {
        $showtime = Schedule::find($schedule)->showtimes;
        return $showtime;
    }

    public function availableShowtimes($auditorium, $date, $duration, $schedule = null)
    {
        try {
            if ($schedule) {
                $excludedShowtimes = Showtime::whereHas('schedules', function ($query) use ($auditorium, $date, $schedule) {
                $query->where('date', $date)
                    ->where('auditorium_id', $auditorium)
                    ->whereNot('id', (int) $schedule);
            })
                ->select(['start_time', 'end_time']);
        } else {
            $excludedShowtimes = Showtime::whereHas('schedules', function ($query) use ($auditorium, $date, $schedule) {
                $query->where('date', $date)
                    ->where('auditorium_id', $auditorium);
            })
                ->select(['start_time', 'end_time']);
        }

        $showtimes = Showtime::whereNotExists(function ($query) use ($excludedShowtimes) {
            $query->selectRaw(1)
                ->fromSub($excludedShowtimes, 'excluded')
                ->whereColumn('showtimes.start_time', '<', 'excluded.end_time')
                ->whereColumn('showtimes.end_time', '>', 'excluded.start_time');
        })
            ->whereRaw('TIMESTAMPDIFF(MINUTE, start_time, end_time) >= ?', [$duration])
            ->orderBy('start_time', 'asc')
            ->get();

            return $showtimes;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getShowtimes(Request $request)
    {
        $action = $request->action;
        switch ($action) {
            case 'for-movie':
                return response()->json($this->withMovieAndDate($request->date, (int) $request->movie));
            case 'for-auditorium':
                return response()->json($this->withAuditorium($request->auditorium));
            case 'for-available':
                if ($request->has('schedule')) {
                    return response()->json($this->availableShowtimes($request->auditorium, $request->date, $request->duration, $request->schedule));
                } else {
                    return response()->json($this->availableShowtimes($request->auditorium, $request->date, $request->duration));
                }
            case 'for-schedule':
                return response()->json($this->withSchedule($request->schedule));
            case 'for-duplicate':
                return response()->json($this->getDullicateShowtimes($request->auditorium, $request->date));
            }
    }
}

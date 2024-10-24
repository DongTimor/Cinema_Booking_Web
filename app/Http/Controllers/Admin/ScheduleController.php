<?php

namespace App\Http\Controllers\Admin;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\Auditorium;
use App\Models\Showtime;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        $auditoriums = Auditorium::all();
        $schedules = Schedule::with(['movie'])->get()
            ->map(function ($schedule) {
                $schedule->setAttribute('movie_title', $schedule->movie->name);
                return $schedule;
            });

        return view('schedules.index', compact('schedules', 'movies', 'auditoriums'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $movies = Movie::where('end_date', '>=', Carbon::today())->get();
        $auditoriums = Auditorium::all();
        return view('schedules.create', compact('movies', 'auditoriums'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ScheduleRequest $request)
    {
        $formattedDate = Carbon::createFromFormat('m/d/Y', $request->date)->format('Y-m-d');
        $request['date'] = $formattedDate;
        $existingSchedule = Schedule::where('movie_id', $request->movie_id)
            ->where('auditorium_id', $request->auditorium_id)
            ->where('date', $formattedDate)
            ->first();
        if ($existingSchedule) {
            if ($request->has('showtime_id')) {
                foreach ($request->showtime_id as $showtime) {
                    $existingSchedule->showtimes()->attach($showtime);
                }
            }
        } else {
            $schedule = Schedule::create($request->all());
            if ($request->has('showtime_id')) {
                foreach ($request->showtime_id as $showtime) {
                    $schedule->showtimes()->attach($showtime);
                }
            }
        }

        return redirect(route('schedules.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Schedule $schedule)
    {

        $movies = Movie::all();
        $auditoriums = Auditorium::all();
        $showtimes = Showtime::all();
        return view('schedules.edit', compact('schedule', 'movies', 'auditoriums', 'showtimes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $schedule->showtimes()->sync($request->showtime_id);
        return redirect(route('schedules.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
    }

    public function deleteShowtimes($scheduleId, $showtimeId)
    {
        Schedule::findOrFail($scheduleId)->showtimes()->detach($showtimeId);
    }

    public function getSchedule($movieId, $date, $auditorium)
    {
        $schedule = Schedule::where('movie_id', $movieId)
            ->whereDate('date', $date)
            ->where('auditorium_id', $auditorium)
            ->select('id')
            ->first();

        $id = $schedule ? $schedule->id : null;
        return response()->json($id);
    }

    public function getDatesOfMovieAndAuditorium($movie, $auditorium)
    {
        $dates = Schedule::where('movie_id', $movie)
            ->where('auditorium_id', $auditorium)
            ->get()
            ->map(function ($schedule) {
                return $schedule->date;
            })
            ->unique();
        return response()->json($dates);
    }

    public function getDateOfMovieAndShowtime($movie, $showtime)
    {
        $date = Schedule::where('movie_id', $movie)
            ->whereHas('showtimes', function ($query) use ($showtime) {
                $query->where('showtime_id', $showtime);
            })
            ->get()
            ->map(function ($schedule) {
                return $schedule->date;
            })
            ->unique();
        return response()->json($date);
    }
}

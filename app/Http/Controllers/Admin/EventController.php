<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventRequest;
use App\Models\Event;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $event = Event::create($request->all());
            foreach ($request->movies as $movie) {
                $event->movies()->attach($movie);
            }
            return response()->json(['success' => 'Successfully created event']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $event)
    {
        $event = Event::findorFail($event);
        try {
            $event->update($request->all());
            $movies = [];
            if ($request->has('start_date')) {
                $movies = Movie::whereNot(function ($query) use ($request) {
                    $query->where('start_date', '>', $request->end_date)
                        ->orWhere('end_date', '<', $request->start_date);
                })
                    ->pluck('id');
                $event->movies()->detach($event->movies()->whereNotIn('id', $movies)->pluck('id'));
            } else {
                if ($request->all_movies) {
                    $event->movies()->sync([]);
                } else {
                    $event->movies()->sync($request->movies);
                }
            }
            return response()->json(['success' => 'Successfully updated event']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($event)
    {
        $event = Event::findorFail($event);
        try {
            $event->delete();
            return response()->json(['success' => 'Successfully deleted event']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllEvents($id)
    {
        if ($id !== 'all') {
            return response()->json(Event::findOrFail($id));
        } else {
            return response()->json(Event::all());
        }
    }

    public function getEventsOfDateAndMovie($date, $movie)
    {
        $events = Event::where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->where(function ($query) use ($movie) {
                $query->whereHas('movies', function ($query) use ($movie) {
                    $query->where('id', $movie);
                })
                ->orWhere('all_movies', true);
            })
            ->with('movies')
            ->get();
        return response()->json($events);
    }
}

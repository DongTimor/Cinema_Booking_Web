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
            if ($request->all_movies) {
                Movie::whereNot(function ($query) use ($request) {
                    $query->where('start_date', '>', $request->end_date)
                        ->orWhere('end_date', '<', $request->start_date);
                })->where('event_id', null)->update(['event_id' => $event->id]);
            } else {
                foreach ($request->movies as $movie) {
                    Movie::findOrFail($movie)->update(['event_id' => $event->id]);
                }
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

            if ($request->all_movies) {
                Movie::whereNot(function ($query) use ($event) {
                    $query->where('start_date', '>', $event->end_date)
                        ->orWhere('end_date', '<', $event->start_date);
                })->where('event_id', null)
                        ->update(['event_id' => $event->id]);
            } else {
                $event->movies()->update(['event_id' => null]);
                foreach ($request->movies as $movie) {
                    Movie::findOrFail($movie)->update(['event_id' => $event->id]);
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
            $event->movies()->update(['event_id' => null]);
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
        try {
            $event = Movie::findOrFail($movie)
                ->event()
                ->where('end_date', '>=', $date)
                ->first();

            return response()->json($event);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

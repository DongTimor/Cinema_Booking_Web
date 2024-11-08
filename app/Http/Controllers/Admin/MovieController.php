<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Category;
use App\Models\Event;
use App\Models\Movie;
use App\Models\Schedule;
use App\Models\Showtime;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::all();
        $today = Carbon::today();
        foreach ($movies as $movie) {
            if ($today->lt($movie->start_date)) {
                $movie->status = 'impending';
            } elseif ($today->between($movie->start_date, $movie->end_date)) {
                $movie->status = 'active';
            } else {
                $movie->status = 'inactive';
            }
            $movie->save();
        }
        return view('admin.movies.feature.index', compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $categories = Category::all();
            return view('admin.movies.feature.create', compact('categories'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MovieRequest $request)
    {
        $validated = $request->validated();
        $validated['start_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['start_date'])->format('Y-m-d');
        $validated['end_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['end_date'])->format('Y-m-d');

        try {
            $movie = Movie::create($validated);
            if ($request->has('poster_urls')) {
                $posterUrls = explode(',', $request->input('poster_urls'));
                foreach ($posterUrls as $url) {
                    $movie->images()->create([
                        'url' => $url,
                        'type' => 'poster',
                    ]);
                }
            }
            if ($request->has('banner_urls')) {
                $bannerUrls = explode(',', $request->input('banner_urls'));
                foreach ($bannerUrls as $url) {
                    $movie->images()->create([
                        'url' => $url,
                        'type' => 'banner',
                    ]);
                }
            }
            if ($request->has('category_id')) {
                $movie->categories()->attach($request->category_id);
            }
            return redirect(route('movies.features.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle the image upload.
     */
    public function uploadImages(Request $request)
    {
        $this->validate($request, [
            'file' => 'required|image|max:2048',
            'type' => 'required|in:poster,banner',
        ]);
        try {
            $request->validate([
                'file' => 'required|image|max:2048',
            ]);

            $url = $request->file('file')->store('movies');

            return response()->json([
                'url' => $url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            return view('admin.movies.feature.show', compact('movie'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Show error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $movie->start_date)->format('m/d/Y');
            $movie->end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $movie->end_date)->format('m/d/Y');
            $categories = Category::all();
            $posters = $movie->images()->where('type', 'poster')->get();
            $banners = $movie->images()->where('type', 'banner')->get();
            return view('admin.movies.feature.edit', compact('movie', 'categories', 'posters', 'banners'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Navigate error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MovieRequest $request, string $id)
    {
        $validated = $request->validated();
        $validated['start_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['start_date'])->format('Y-m-d');
        $validated['end_date'] = \Carbon\Carbon::createFromFormat('m/d/Y', $validated['end_date'])->format('Y-m-d');
    
        try {
            $movie = Movie::findOrFail($id);
            $movie->update($validated);
    
            $posterUrls = $request->input('poster_urls', []);
            $bannerUrls = $request->input('banner_urls', []);
            foreach ($movie->images as $image) {
                $path = storage_path('app/' . $image->url);
                if (
                    ($image->type == 'poster' && !in_array($image->url, $posterUrls)) ||
                    ($image->type == 'banner' && !in_array($image->url, $bannerUrls))
                ) {
                    if (file_exists($path)) {
                        File::delete($path);
                    }
                    $image->delete();
                }
            }
            foreach ($posterUrls as $url) {
                $movie->images()->updateOrCreate(
                    ['url' => $url, 'type' => 'poster'],
                    ['url' => $url]
                );
            }
            foreach ($bannerUrls as $url) {
                $movie->images()->updateOrCreate(
                    ['url' => $url, 'type' => 'banner'],
                    ['url' => $url]
                );
            }
            if ($request->has('category_id')) {
                $movie->categories()->sync($request->category_id);
            }
            return redirect()->route('movies.features.index')->with('success', 'Movie updated successfully!');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();
            return redirect(route('movies.features.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getShowtimes(string $id)
    {
        $showtimes = Showtime::where('movie_id', $id)->get();
        return response()->json($showtimes);
    }

    public function getDuration(string $id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie->duration);
    }

    public function getDates(string $id)
    {
        $dates = Movie::findOrFail($id)->start_date;
        return response()->json($dates);
    }

    public function getSchedule(string $id)
    {
        $schedules = Schedule::where('movie_id', $id)->get();
        $showtimes = $schedules->flatMap(function ($schedule) {
            return $schedule->showtimes;
        })->unique('id');

        $auditoriums = $schedules->map(function ($schedule) {
            return $schedule->auditorium;
        })->unique('id');

        $dates = $schedules->map(function ($schedule) {
            return $schedule->date;
        })->unique();

        $information = [
            'showtimes' => $showtimes,
            'auditoriums' => $auditoriums,
            'dates' => $dates,
        ];
        return response()->json($information);
    }

    public function getPrice(string $id)
    {
        $movie = Movie::findOrFail($id);
        return response()->json($movie->price);
    }

    public function getMoviesOfDates($start_date, $end_date)
    {
        $movies = Movie::whereNot(function ($query) use ($start_date, $end_date) {
            $query->where('start_date', '>', $end_date)
                ->orWhere('end_date', '<', $start_date);
        })
            ->get();
        return response()->json($movies);
    }

    public function getMoviesOfEvent($id)
    {
        $event = Event::findOrFail($id);
        return response()->json($event->movies);
    }
}

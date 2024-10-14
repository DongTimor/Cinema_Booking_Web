<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MovieRequest;
use App\Models\Auditorium;
use App\Models\Category;
use App\Models\Movie;
use App\Models\Showtime;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $movies = Movie::paginate(10);
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
            if ($request->hasFile('image_id')) {
                $images = $request->file('image_id');
                foreach ($images as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $imagePath = $image->storeAs('public/images', $imageName);
                    $movie->images()->create([
                        'url' => str_replace('public/', 'storage/', $imagePath),
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $movie = Movie::findOrFail($id);
            $images = $movie->images;
            $movie->start_date = \Carbon\Carbon::createFromFormat('Y-m-d', $movie->start_date)->format('m/d/Y');
            $movie->end_date = \Carbon\Carbon::createFromFormat('Y-m-d', $movie->end_date)->format('m/d/Y');
            $categories = Category::all();
            return view('admin.movies.feature.edit', compact('movie', 'categories', 'images'));
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
            if ($request->hasFile('image_id')) {
                $images = $request->file('image_id');
                foreach ($images as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $imagePath = $image->storeAs('public/images', $imageName);
                    $movie->images()->create([
                        'url' => str_replace('public/', 'storage/', $imagePath),
                    ]);
                }
            }
            if ($request->has('category_id')) {
                $movie->categories()->sync($request->category_id);
            }
            return redirect(route('movies.features.index'));
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
            $movie = Movie::findorFail($id);
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
}

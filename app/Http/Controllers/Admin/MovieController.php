<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin.movie.feature.index',compact('movies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try{
            $categories = Category::all();
            return view('admin.movie.feature.create',compact('categories'));
        }catch (\Exception $e){
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getShowtimes(string $id)
    {
        $showtimes = Showtime::where('movie_id', $id)->get();
        return response()->json($showtimes);
    }
}

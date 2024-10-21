<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $movies = Movie::with('images')->get(); 
        return view('welcome',compact('movies'));
    }

    public function detail($id)
    {
        $movie = Movie::findOrFail($id);
        return view('customer.movie-detail',compact('movie'));
    }
}



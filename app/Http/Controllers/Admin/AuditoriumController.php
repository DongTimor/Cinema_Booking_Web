<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auditorium;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AuditoriumController extends Controller
{
    public function index()
    {
        try {
            $auditorums = Auditorium::paginate(10);
            return view('admin.auditoriums.index', ['auditoriums' => $auditorums]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return view('admin.auditoriums.create');
    }

    public function store(Request $req)
    {
        try {
            $validate = $req->validate([
                'name' => 'required|string|max:255',
                'total' => 'required|integer|min:1'
            ]);
            Auditorium::create($validate);
            return redirect(route('auditoriums.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $auditorium = Auditorium::findOrFail($id);
            $auditorium->delete();
            return redirect(route('auditoriums.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $auditorium = Auditorium::findOrFail($id);
            return view('admin.auditoriums.show', compact('auditorium'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Show error', 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $auditorium = Auditorium::findOrFail($id);
            return view('admin.auditoriums.edit', compact('auditorium'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Navigate error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $req, $id)
    {
        try {
            $validate = $req->validate([
                'name' => 'required|string|max:255',
                'total' => 'required|integer|min:1'
            ]);
            $auditorium = Auditorium::findOrFail($id);
            $auditorium->update($validate);
            return redirect(route('auditoriums.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Update error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getTotalSeats($id)
    {
        $total = Auditorium::findOrFail($id)->total;
        return $total;
    }

    public function getTotalAvailableSeats($id)
    {
        $total = Auditorium::findOrFail($id)->seats()->count();
        return $total;
    }

    public function getAuditoriumsOfShowtime($date, $movie, $showtime)
    {
        $auditoriums = Schedule::whereDate('date', $date)
            ->where('movie_id', $movie)
            ->whereHas('showtimes', function ($query) use ($showtime) {
                $query->where('id', $showtime);
            })
            ->with('auditorium')
            ->get()
            ->map(function ($schedule) {
                return [
                    'auditorium_id' => $schedule->auditorium_id,
                    'auditorium' => $schedule->auditorium->name
                ];
            })
            ->unique('auditorium_id')
            ->values();

        return response()->json($auditoriums);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Auditorium;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;

class AuditoriumController extends Controller
{
    public function index() {
        try {
            $auditorums = Auditorium::paginate(15);
            // return response()->json($auditorums);
            return view('test', ['auditoriums' => $auditorums]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
 
    }
    public function create() {
        return view('create_auditorium');
    }
    public function store(Request $req){
        try {
            $validate = $req->validate([
                'name' => 'required|string|max:255',
            ]);
            Auditorium::create($validate);
            return response()->json(['message' => 'Create success'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

}

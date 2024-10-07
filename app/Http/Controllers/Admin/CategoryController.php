<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.movies.category.index',compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.movies.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        try{
            $validate= $req->validate([
                'name' => 'required|string|max:255',
            ]);
            Category::create($validate);
            return redirect(route('movies.categories.index'));
        }catch (\Exception $e){
            return response()->json(['error' => 'Create error', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.movies.category.show', compact('category'));
        } catch (\Exception $e) {
            abort(404, 'Category not found');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( string $id)
    {
   
        try{
            $category = Category::findOrFail($id);
            return view('admin.movies.category.edit',compact('category'));
        } catch (\Exception $e){
            return response()->json(['error' => 'Open error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        try{
            $validate = $req->validate([
                'name' => 'required|string|max:255'
            ]);
            $category = Category::findOrFail($id);
            $category->update($validate);
            return redirect(route('movies.categories.index'));
        }catch (\Exception $e){
            return response()->json(['error' => 'Update error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $category = Category::findOrFail($id);
            $category->delete();
            return redirect(route('movies.categories.index'));
        } catch (\Exception $e) {
            return response()->json(['error' => 'Delete error', 'message' => $e->getMessage()], 500);
        }
    }
}

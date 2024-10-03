<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::where('flag_deleted',0)->get();

        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:roles,name',
        ],['name.required'=>'Role name in valid!','name.unique'=>'Role name in unique!']);

        $name = $request->post('name');
        $role = Role::create(
            ['name' => $name]
        );

        $permissions = $request->post('permissions');

        $role->permissions()->attach($permissions);
        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::where('id', $id)->with('permissions')->first();
        $permissions = Permission::all();
        $ids = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.show', compact('role','permissions','ids'));
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
    public function update(Request $request, $roleId)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'permissions' => 'array',
    ]);

    $role = Role::find($roleId);

    if ($role) {
        if ($request->has('name') || $request->has('flag_deleted')) {
            $data = $request->only(['name', 'flag_deleted']);
            $role->updateRole($data);
        }

        $role->updatePermissions($request->input('permissions'));

        return redirect()->route('roles.index');
    }

    return redirect()->route('roles.index');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $role = Role::where('id', $id)
        ->where('flag_deleted',0)->first();

        $role->update([
            'flag_deleted' => 1,
        ]);
    }
}

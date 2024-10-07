<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::get()->pluck('name', 'id');
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|unique:roles,name',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:8',
        ]);

        $name = $request->post('name');
        $email = $request->post('email');
        $password = $request->post('password');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
        ]);

        $roleId = Role::assignRole('user');

        $user->roles()->attach($roleId);

        return redirect()->route('users.index');
    }
}

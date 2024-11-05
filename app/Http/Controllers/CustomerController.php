<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('admin.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.customers.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:11',
        ]);
        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'image' => $request->image,
            'status' => $request->status ?? 0,
        ]);
        $imagePath = null;
        if ($request->hasFile('image')) {
            if ($customer->image) {
                Storage::delete(str_replace('storage', 'public', $customer->image));
            }
            $url = $request->file('image')->store('public/images');
            $imagePath = Storage::url($url);
        }
        $customer->image = $imagePath;
        $customer->save();
        return redirect()->route('customers.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Customer::findOrFail($id);
        $roles = Role::all();
        $ids = $customer->roles->pluck('id')->toArray();
        return view('admin.customers.edit', compact('customer', 'roles', 'ids'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'required|string|min:8',
        'phone_number' => 'required|string|max:11',
    ]);
    $customer = Customer::where('id', $id)->first();
    $customer->update([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'phone_number' => $request->phone_number,
        'address' => $request->address,
        'gender' => $request->gender,
        'birth_date' => $request->birth_date,
        'image' => $request->image,
        'status' => $request->status
    ]);
    $roles = $request->post('roles');
    $customer->roles()->sync($roles);
    if ($request->hasFile('image')) {
        if ($customer->image) {
            Storage::delete(str_replace('storage', 'public', $customer->image));
        }
        $url = $request->file('image')->store('public/images');
        $customer->image = Storage::url($url);
    }
    $customer->save();
    return redirect()->route('customers.index');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = Customer::where('id', $id)->first();
        $customer->delete();
        return redirect()->route('customers.index');
    }

    public function getCustomerInfor(string $id)
    {
        $customer = Customer::findOrFail($id)
            ->select('id', 'name', 'email', 'phone_number', 'address', 'gender', 'birth_date')
            ->first();
        return response()->json($customer);
    }
}

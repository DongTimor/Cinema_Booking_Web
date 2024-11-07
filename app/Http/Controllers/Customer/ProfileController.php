<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('customer.profile', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'string|min:10',
            'address' => 'string|max:255',
            'birth_date' => 'date',
        ]);

        $customer = Customer::findOrFail($id);
        if ($request->hasFile('image')) {
            if ($customer->image) {
                $path = storage_path('app/' . $customer->image);
                if (File::exists($path)) {
                    File::delete($path);
                }
            }
            $url = $request->file('image')->store('images');
            $customer->update(['image' => $url]);
        }
        $customer->update($request->except('image'));
        return redirect()->route('home')->with('success', 'Profile updated successfully!');
    }
}

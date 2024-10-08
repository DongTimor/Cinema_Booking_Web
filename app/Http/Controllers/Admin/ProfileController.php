<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        $userId = auth()->id();
        $profile = Profile::firstOrCreate(['user_id' => $userId]);
        return view('admin.profiles.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
            'birth_date' => 'required',
        ], [
            'phone.required' => 'Phone is required',
            'address.required' => 'Address is required',
            'birth_date.required' => 'Birthday is required'
        ]);

        $profileData = $request->all();

        $profile = Profile::where('user_id', $id)->first();
        if ($request->hasFile('image')) {
            if ($profile->image) {
                Storage::delete(str_replace('storage', 'public',$profile->image));
            }
            $url = $request->file('image')->store('public/images');
            $profileData['image'] = Storage::url($url);
        }

        $profile->update($profileData);
        return redirect()->route('dashboards.index');
    }
}

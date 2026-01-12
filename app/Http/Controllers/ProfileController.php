<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:191',
            'profile_photo' => 'nullable|image|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        // handle profile photo
        if ($request->hasFile('profile_photo')) {
            try {
                $file = $request->file('profile_photo');
                // verify validity
                if (!$file->isValid()) {
                    return back()->withErrors(['profile_photo' => 'Uploaded file is not valid.']);
                }

                $path = $file->store('profile-photos', 'public');

                // delete old if exists
                if ($user->profile_photo_path) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }

                $user->profile_photo_path = $path;
            } catch (\Exception $e) {
                return back()->withErrors(['profile_photo' => 'Image upload failed: ' . $e->getMessage()]);
            }
        }

        $user->name = $data['name'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated.');
    }

    // keep destroy if routes expect it, but will not be linked from UI
    public function destroy(Request $request)
    {
        // intentionally left simple — UI delete removed
        return redirect()->route('profile.edit');
    }
}

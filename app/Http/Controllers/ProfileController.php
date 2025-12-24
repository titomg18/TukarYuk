<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update the authenticated user's profile (basic fields).
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'location' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        // Email is not editable from settings; preserve existing email
        $user->fill([
            'name' => $data['name'],
            'phone' => $data['phone'] ?? $user->phone,
            'location' => $data['location'] ?? $user->location,
            'bio' => $data['bio'] ?? $user->bio,
        ]);

        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Profil diperbarui'], 200);
        }

        return redirect()->back()->with('success', 'Profil diperbarui');
    }
}

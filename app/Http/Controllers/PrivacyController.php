<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PrivacyController extends Controller
{
    /**
     * Update the authenticated user's privacy settings.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'profile_visibility' => ['nullable', Rule::in(['public', 'private'])],
            'show_email' => ['nullable', 'in:1'],
            'show_phone' => ['nullable', 'in:1'],
            'show_location' => ['nullable', 'in:1'],
        ]);

        if (array_key_exists('profile_visibility', $data)) {
            $user->profile_visibility = $data['profile_visibility'];
        }

        $user->show_email = $request->has('show_email');
        $user->show_phone = $request->has('show_phone');
        $user->show_location = $request->has('show_location');

        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan privasi disimpan'], 200);
        }

        return redirect()->back()->with('success', 'Pengaturan privasi disimpan');
    }
}

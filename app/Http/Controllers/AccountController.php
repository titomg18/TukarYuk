<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AccountController extends Controller
{
    /**
     * Delete the authenticated user's account.
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        if (! $user) {
            return redirect()->route('login');
        }

        $userId = $user->id;

        // Log out and invalidate session first
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Delete the user record (and rely on DB cascade for related data if configured)
        User::where('id', $userId)->delete();

        return redirect()->route('login')->with('success', 'Akun Anda telah dihapus.');
    }
}

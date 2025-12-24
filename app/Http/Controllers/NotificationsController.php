<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    /**
     * Update the authenticated user's notification preferences.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'email_notifications' => ['nullable', 'in:1'],
            'swap_notifications' => ['nullable', 'in:1'],
            'message_notifications' => ['nullable', 'in:1'],
            'marketing_emails' => ['nullable', 'in:1'],
        ]);

        $user->email_notifications = $request->has('email_notifications');
        $user->swap_notifications = $request->has('swap_notifications');
        $user->message_notifications = $request->has('message_notifications');
        $user->marketing_emails = $request->has('marketing_emails');

        $user->save();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Pengaturan notifikasi diperbarui'], 200);
        }

        return redirect()->back()->with('success', 'Pengaturan notifikasi disimpan');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PushSubscriptionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'endpoint' => ['required', 'url'],
            'keys' => ['required', 'array'],
            'keys.auth' => ['required', 'string'],
            'keys.p256dh' => ['required', 'string'],
        ]);

        $request->user()->updatePushSubscription(
            $validated['endpoint'],
            $validated['keys']['p256dh'],
            $validated['keys']['auth'],
        );

        return response()->json(['success' => true]);
    }

    public function vapidPublicKey()
    {
        $key = config('webpush.vapid.public_key');

        if (! $key) {
            throw ValidationException::withMessages([
                'vapid' => 'Push notifications are not configured. Set VAPID keys in your environment.',
            ]);
        }

        return response()->json(['publicKey' => $key]);
    }
}
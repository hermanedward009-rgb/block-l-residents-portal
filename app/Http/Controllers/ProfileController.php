<?php

namespace App\Http\Controllers;

use App\Models\CleaningSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $cleaning = CleaningSchedule::query()
            ->whereHas('room', fn ($q) => $q->where('number', $user->room_number))
            ->orderBy('day_of_week')
            ->with(['room.activeResidents'])
            ->get();

        return view('profile.show', compact('user', 'cleaning'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $validated['profile_picture'] = $request->file('profile_picture')->store('avatars', 'public');
        } else {
            unset($validated['profile_picture']);
        }

        $user->update($validated);

        return back()->with('status', 'Profile updated successfully.');
    }
}
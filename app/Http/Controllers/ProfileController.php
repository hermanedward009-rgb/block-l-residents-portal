<?php

namespace App\Http\Controllers;

use App\Models\CleaningSchedule;
use App\Services\PortalFileUpload;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            'profile_picture' => PortalFileUpload::imageRules(),
        ]);

        if ($request->hasFile('profile_picture')) {
            $validated['profile_picture'] = PortalFileUpload::storeProfileImage(
                $request->file('profile_picture'),
                $user->profile_picture,
            );
        } else {
            unset($validated['profile_picture']);
        }

        $user->update($validated);

        return back()->with('status', __('portal.profile_updated'));
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => $validated['password'],
        ]);

        return back()->with('status', __('portal.password_updated'));
    }
}
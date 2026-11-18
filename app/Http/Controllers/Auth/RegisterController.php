<?php

namespace App\Http\Controllers\Auth;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Services\PortalFileUpload;
use App\Services\PortalNotifier;
use App\Services\RoomRegistrationGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register', [
            'rooms' => Room::query()->orderBy('number')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:30'],
            'room_number' => ['required', 'integer', Rule::in(range(1, 10))],
            'password' => ['required', 'confirmed', Password::defaults()],
            'profile_picture' => PortalFileUpload::imageRules(),
        ]);

        if (! RoomRegistrationGuard::roomAvailable((int) $validated['room_number'])) {
            return back()
                ->withInput()
                ->withErrors([
                    'room_number' => 'This room already has the maximum of 2 active or pending members. Choose another room or contact the administrator.',
                ]);
        }

        $profilePath = $request->hasFile('profile_picture')
            ? PortalFileUpload::storeProfileImage($request->file('profile_picture'))
            : null;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'room_number' => $validated['room_number'],
            'password' => Hash::make($validated['password']),
            'profile_picture' => $profilePath,
            'role' => UserRole::Resident,
            'status' => AccountStatus::Pending,
        ]);

        PortalNotifier::notifyAdmins(
            'registration',
            'New Block L registration',
            "{$user->name} (Room {$user->room_number}) registered and awaits approval.",
            ['user_id' => $user->id],
        );

        PortalNotifier::log($user, 'registration', "Resident {$user->name} registered for Room {$user->room_number}.");

        auth()->login($user);

        return redirect()->route('pending-approval');
    }
}
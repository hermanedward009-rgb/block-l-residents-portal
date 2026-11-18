<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\User;
use App\Services\PortalNotifier;
use App\Services\RoomRegistrationGuard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('room_number'), fn ($q) => $q->where('room_number', $request->integer('room_number')))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $rooms = Room::query()->orderBy('number')->get();

        return view('admin.users.index', compact('users', 'rooms'));
    }

    public function approve(Request $request, User $user)
    {
        if ($user->room_number && ! RoomRegistrationGuard::roomAvailable((int) $user->room_number, $user->id)) {
            return back()->withErrors(['room' => 'This room already has 2 members. Reject or reassign before approving.']);
        }

        $user->update([
            'status' => AccountStatus::Approved,
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);

        PortalNotifier::notifyUser(
            $user,
            'approval',
            'Account approved',
            'Welcome to Block L Residents Portal. Your account has been approved.',
        );

        PortalNotifier::log($request->user(), 'user_approved', "Approved resident {$user->name}.");

        return back()->with('status', "Approved {$user->name}.");
    }

    public function reject(User $user)
    {
        $user->update(['status' => AccountStatus::Rejected]);

        PortalNotifier::notifyUser(
            $user,
            'rejection',
            'Registration rejected',
            'Your Block L registration was not approved. Please contact the administrator.',
        );

        return back()->with('status', "Rejected {$user->name}.");
    }

    public function deactivate(User $user)
    {
        if ($user->isAdmin()) {
            return back()->withErrors(['user' => 'Cannot deactivate administrator accounts from this action.']);
        }

        $user->update(['status' => AccountStatus::Deactivated]);

        PortalNotifier::notifyUser($user, 'deactivated', 'Account deactivated', 'Your Block L portal access has been deactivated.');

        return back()->with('status', "Deactivated {$user->name}.");
    }

    public function reactivate(User $user)
    {
        $user->update([
            'status' => AccountStatus::Approved,
            'approved_at' => $user->approved_at ?? now(),
        ]);

        PortalNotifier::notifyUser($user, 'reactivated', 'Account reactivated', 'Your Block L portal access has been restored.');

        return back()->with('status', "Reactivated {$user->name}.");
    }

    public function updateRoom(Request $request, User $user)
    {
        $validated = $request->validate([
            'room_number' => ['required', 'integer', Rule::in(range(1, 10))],
        ]);

        if (! RoomRegistrationGuard::roomAvailable((int) $validated['room_number'], $user->id)) {
            return back()->withErrors(['room_number' => 'Room already has 2 members.']);
        }

        $user->update(['room_number' => $validated['room_number']]);

        return back()->with('status', 'Room assignment updated.');
    }

    public function updateRoomLabel(Request $request, Room $room)
    {
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:100'],
        ]);

        $room->update(['label' => $validated['label']]);

        return back()->with('status', "Room name updated to \"{$room->label}\".");
    }

    public function resetResidents()
    {
        User::query()
            ->where('role', UserRole::Resident)
            ->each(function (User $user) {
                $user->delete();
            });

        Cache::forget('blockl.dashboard.stats');

        return back()->with('status', 'All resident accounts have been removed. Registration is open for a fresh start.');
    }
}
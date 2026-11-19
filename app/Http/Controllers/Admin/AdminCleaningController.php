<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\CleaningSchedule;
use App\Models\Room;
use App\Models\User;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminCleaningController extends Controller
{
    public function index()
    {
        $schedules = CleaningSchedule::query()
            ->with(['room.activeResidents', 'assignedUser', 'completedByUser'])
            ->orderBy('day_of_week')
            ->orderBy('toilet_type')
            ->get();

        return view('admin.cleaning.index', [
            'schedules' => $schedules,
            'schedulesByDay' => $schedules->groupBy('day_of_week'),
            'residents' => $this->residentsForRota(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'assigned_user_id' => ['required', 'exists:users,id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'toilet_type' => ['required', 'in:small,large'],
            'scheduled_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $this->applyAssigneeRoom($validated);

        CleaningSchedule::create($validated);

        PortalNotifier::notifyApprovedResidents(
            'cleaning',
            'Cleaning schedule updated',
            'The Block L cleaning timetable has been updated.',
        );

        PortalNotifier::log($request->user(), 'cleaning_update', 'Cleaning schedule entry added.');

        return back()->with('status', 'Cleaning schedule entry added.');
    }

    public function update(Request $request, CleaningSchedule $schedule)
    {
        $validated = $request->validate([
            'assigned_user_id' => ['required', 'exists:users,id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'toilet_type' => ['required', 'in:small,large'],
            'scheduled_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $this->applyAssigneeRoom($validated);

        $schedule->update($validated);

        PortalNotifier::log($request->user(), 'cleaning_update', "Updated cleaning duty ({$validated['toilet_type']}).");

        PortalNotifier::notifyApprovedResidents(
            'cleaning',
            'Cleaning schedule updated',
            'The Block L cleaning timetable has been updated.',
        );

        return back()->with('status', 'Schedule updated.');
    }

    public function complete(Request $request, CleaningSchedule $schedule)
    {
        $schedule->update([
            'is_completed' => true,
            'completed_at' => now(),
            'completed_by' => $request->user()->id,
        ]);

        $names = implode(', ', $schedule->dutyNames()) ?: 'unassigned';
        PortalNotifier::log($request->user(), 'cleaning_complete', "Marked cleaning complete for {$names}.");

        return back()->with('status', 'Cleaning task marked complete.');
    }

    public function destroy(CleaningSchedule $schedule)
    {
        $schedule->delete();

        return back()->with('status', 'Schedule entry removed.');
    }

    /** @return \Illuminate\Database\Eloquent\Collection<int, User> */
    private function residentsForRota()
    {
        return User::query()
            ->where('role', UserRole::Resident)
            ->whereIn('status', [AccountStatus::Approved, AccountStatus::Pending])
            ->whereNotNull('room_number')
            ->orderBy('name')
            ->get();
    }

    /** @param  array<string, mixed>  $validated */
    private function applyAssigneeRoom(array &$validated): void
    {
        /** @var User $user */
        $user = User::query()->findOrFail($validated['assigned_user_id']);

        if (! $user->room_number) {
            throw ValidationException::withMessages([
                'assigned_user_id' => 'This person has no room — assign them to a room first.',
            ]);
        }

        $room = Room::query()->where('number', $user->room_number)->first();

        if (! $room) {
            throw ValidationException::withMessages([
                'assigned_user_id' => 'Room record missing for this resident.',
            ]);
        }

        $validated['room_id'] = $room->id;
    }
}
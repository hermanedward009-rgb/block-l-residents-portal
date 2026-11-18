<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CleaningSchedule;
use App\Models\Room;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;

class AdminCleaningController extends Controller
{
    public function index()
    {
        return view('admin.cleaning.index', [
            'schedules' => CleaningSchedule::query()->with(['room.activeResidents', 'completedByUser'])->orderBy('day_of_week')->get(),
            'rooms' => Room::query()->orderBy('number')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'toilet_type' => ['required', 'in:small,large'],
            'scheduled_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

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
            'room_id' => ['required', 'exists:rooms,id'],
            'day_of_week' => ['required', 'integer', 'between:0,6'],
            'toilet_type' => ['required', 'in:small,large'],
            'scheduled_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $schedule->update($validated);

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

        PortalNotifier::log($request->user(), 'cleaning_complete', "Marked cleaning complete for room {$schedule->room->number}.");

        return back()->with('status', 'Cleaning task marked complete.');
    }

    public function destroy(CleaningSchedule $schedule)
    {
        $schedule->delete();

        return back()->with('status', 'Schedule entry removed.');
    }
}
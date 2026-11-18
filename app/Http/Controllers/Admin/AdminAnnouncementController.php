<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Room;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdminAnnouncementController extends Controller
{
    public function index()
    {
        return view('admin.announcements.index', [
            'announcements' => Announcement::query()->latest()->with('author')->paginate(15),
            'rooms' => Room::query()->orderBy('number')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'max:5000'],
            'target_room_number' => ['nullable', 'integer', Rule::in(range(1, 10))],
        ]);

        $targetRoom = $validated['target_room_number'] ?? null;

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'target_room_number' => $targetRoom,
            'user_id' => $request->user()->id,
        ]);

        PortalNotifier::notifyApprovedResidents(
            'announcement',
            'New Block L announcement',
            $announcement->title,
            ['announcement_id' => $announcement->id],
            onlyRoomNumber: $targetRoom,
        );

        $scope = $targetRoom ? 'one room' : 'all rooms';
        PortalNotifier::log($request->user(), 'announcement', "Published announcement ({$scope}): {$announcement->title}");

        return back()->with('status', $targetRoom ? 'Announcement sent to the selected room.' : 'Announcement sent to all residents.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();

        return back()->with('status', 'Announcement removed.');
    }
}
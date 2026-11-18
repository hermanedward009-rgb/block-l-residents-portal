<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $room = auth()->user()->room_number;

        $announcements = Announcement::query()
            ->where(function ($query) use ($room) {
                $query->whereNull('target_room_number');
                if ($room) {
                    $query->orWhere('target_room_number', $room);
                }
            })
            ->latest()
            ->with('author')
            ->paginate(10);

        return view('announcements.index', compact('announcements'));
    }
}
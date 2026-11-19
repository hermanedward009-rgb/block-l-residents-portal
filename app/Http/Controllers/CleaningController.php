<?php

namespace App\Http\Controllers;

use App\Models\CleaningSchedule;
use Illuminate\Http\Request;

class CleaningController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $schedules = CleaningSchedule::query()
            ->with(['room.activeResidents', 'completedByUser'])
            ->orderBy('day_of_week')
            ->orderBy('scheduled_date')
            ->get()
            ->groupBy(fn ($item) => CleaningSchedule::dayName($item->day_of_week));

        $myDuties = $user->room_number
            ? CleaningSchedule::query()
                ->with(['room.activeResidents'])
                ->whereHas('room', fn ($q) => $q->where('number', $user->room_number))
                ->orderBy('day_of_week')
                ->get()
            : collect();

        return view('cleaning.index', compact('schedules', 'myDuties', 'user'));
    }
}
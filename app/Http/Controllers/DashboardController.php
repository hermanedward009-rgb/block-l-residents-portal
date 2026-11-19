<?php

namespace App\Http\Controllers;

use App\Enums\AccountStatus;
use App\Enums\PaymentStatus;
use App\Models\AdvicePost;
use App\Models\Announcement;
use App\Models\ActivityLog;
use App\Models\CleaningSchedule;
use App\Models\ElectricityPayment;
use App\Models\ElectricityPeriod;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $activePeriod = ElectricityPeriod::query()->where('is_active', true)->latest()->first();

        $verifiedQuery = ElectricityPayment::query()->where('status', PaymentStatus::Verified);
        $pendingQuery = ElectricityPayment::query()->where('status', PaymentStatus::Pending);

        $todayDuties = CleaningSchedule::query()
            ->with('room.activeResidents')
            ->where('day_of_week', now()->dayOfWeek)
            ->orderBy('toilet_type')
            ->get();

        $upcomingCleaning = CleaningSchedule::query()
            ->with('room.activeResidents')
            ->where('is_completed', false)
            ->orderBy('day_of_week')
            ->limit(6)
            ->get();

        $stats = Cache::remember('blockl.dashboard.stats', 30, function () use ($verifiedQuery, $pendingQuery, $upcomingCleaning) {
            return [
                'total_rooms' => 10,
                'registered_members' => User::query()
                    ->where('status', AccountStatus::Approved)
                    ->whereNotNull('room_number')
                    ->count(),
                'electricity_verified' => (clone $verifiedQuery)->count(),
                'electricity_pending' => (clone $pendingQuery)->count(),
                'upcoming_cleaning' => $upcomingCleaning->count(),
                'announcements' => Announcement::query()->count(),
            ];
        });

        return view('dashboard.index', [
            'stats' => $stats,
            'todayDuties' => $todayDuties,
            'activePeriod' => $activePeriod,
            'totalCollected' => (clone $verifiedQuery)->sum('amount_tzs'),
            'pendingAmount' => (clone $pendingQuery)->sum('amount_tzs'),
            'recentActivities' => ActivityLog::query()->latest()->limit(8)->with('user')->get(),
            'latestAdvice' => AdvicePost::query()->where('is_removed', false)->latest()->limit(5)->with('user')->get(),
            'announcements' => Announcement::query()
                ->where(function ($query) use ($request) {
                    $query->whereNull('target_room_number');
                    $room = $request->user()->room_number;
                    if ($room) {
                        $query->orWhere('target_room_number', $room);
                    }
                })
                ->latest()
                ->limit(5)
                ->with('author')
                ->get(),
            'upcomingCleaning' => $upcomingCleaning,
            'recentPayments' => ElectricityPayment::query()->latest()->limit(5)->with('user')->get(),
        ]);
    }
}
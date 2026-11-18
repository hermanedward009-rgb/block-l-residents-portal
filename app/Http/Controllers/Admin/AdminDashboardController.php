<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AccountStatus;
use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ElectricityPayment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        return view('admin.dashboard', [
            'pendingUsers' => User::query()->where('status', AccountStatus::Pending)->count(),
            'approvedResidents' => User::query()->where('status', AccountStatus::Approved)->whereNotNull('room_number')->count(),
            'pendingPayments' => ElectricityPayment::query()->where('status', PaymentStatus::Pending)->count(),
            'recentLogs' => ActivityLog::query()->latest()->limit(12)->with('user')->get(),
            'pendingRegistrations' => User::query()->where('status', AccountStatus::Pending)->latest()->limit(10)->get(),
            'pendingPaymentRows' => ElectricityPayment::query()->where('status', PaymentStatus::Pending)->latest()->limit(10)->with('user')->get(),
        ]);
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Enums\PaymentStatus;
use App\Http\Controllers\Controller;
use App\Models\ElectricityPayment;
use App\Models\ElectricityPeriod;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;

class AdminElectricityController extends Controller
{
    public function verify(Request $request, ElectricityPayment $payment)
    {
        $payment->update([
            'status' => PaymentStatus::Verified,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'rejection_reason' => null,
        ]);

        PortalNotifier::notifyUser(
            $payment->user,
            'electricity_verified',
            'Electricity payment verified',
            'Your payment of TZS '.number_format((float) $payment->amount_tzs, 2).' has been verified.',
            ['payment_id' => $payment->id],
        );

        PortalNotifier::log($request->user(), 'electricity_verified', "Verified payment #{$payment->id}.");

        return back()->with('status', 'Payment verified.');
    }

    public function reject(Request $request, ElectricityPayment $payment)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:500'],
        ]);

        $payment->update([
            'status' => PaymentStatus::Rejected,
            'rejection_reason' => $validated['rejection_reason'],
            'verified_at' => null,
            'verified_by' => null,
        ]);

        PortalNotifier::notifyUser(
            $payment->user,
            'electricity_rejected',
            'Electricity payment rejected',
            'Your payment was rejected: '.$validated['rejection_reason'],
            ['payment_id' => $payment->id],
        );

        return back()->with('status', 'Payment rejected.');
    }

    public function periods()
    {
        return view('admin.electricity.periods', [
            'periods' => ElectricityPeriod::query()->latest()->get(),
            'active' => ElectricityPeriod::query()->where('is_active', true)->first(),
        ]);
    }

    public function storePeriod(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:150'],
            'amount_tzs' => ['required', 'numeric', 'min:1'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'payment_deadline' => ['nullable', 'date', 'after_or_equal:period_start'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($request->boolean('is_active')) {
            ElectricityPeriod::query()->update(['is_active' => false]);
        }

        ElectricityPeriod::create([
            ...$validated,
            'is_active' => $request->boolean('is_active'),
            'created_by' => $request->user()->id,
        ]);

        PortalNotifier::notifyApprovedResidents(
            'electricity_period',
            'Electricity contribution period updated',
            "New period: {$validated['title']} — TZS ".number_format((float) $validated['amount_tzs'], 2),
        );

        return back()->with('status', 'Electricity period saved.');
    }

    public function activatePeriod(ElectricityPeriod $period)
    {
        ElectricityPeriod::query()->update(['is_active' => false]);
        $period->update(['is_active' => true]);

        PortalNotifier::notifyApprovedResidents(
            'electricity_period',
            'Active electricity period',
            "Active period set to: {$period->title}",
        );

        return back()->with('status', 'Period activated.');
    }
}
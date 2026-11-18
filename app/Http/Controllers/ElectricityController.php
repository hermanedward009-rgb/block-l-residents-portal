<?php

namespace App\Http\Controllers;

use App\Enums\PaymentStatus;
use App\Models\ElectricityPayment;
use App\Models\ElectricityPeriod;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ElectricityController extends Controller
{
    public function index(Request $request)
    {
        $activePeriod = ElectricityPeriod::query()->where('is_active', true)->latest()->first();

        $query = ElectricityPayment::query()->with(['user', 'period'])->latest();

        if ($request->filled('room_number')) {
            $query->where('room_number', $request->integer('room_number'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        if ($request->filled('resident')) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$request->string('resident').'%'));
        }
        if ($request->filled('payment_date')) {
            $query->whereDate('payment_date', $request->date('payment_date'));
        }

        $payments = $query->paginate(15)->withQueryString();

        $verified = ElectricityPayment::query()->where('status', PaymentStatus::Verified);
        $pending = ElectricityPayment::query()->where('status', PaymentStatus::Pending);

        $outstanding = collect();
        if ($activePeriod) {
            $paidRooms = ElectricityPayment::query()
                ->where('electricity_period_id', $activePeriod->id)
                ->where('status', PaymentStatus::Verified)
                ->pluck('room_number')
                ->unique();
            $outstanding = collect(range(1, 10))->diff($paidRooms->all());
        }

        return view('electricity.index', [
            'payments' => $payments,
            'activePeriod' => $activePeriod,
            'summary' => [
                'total_collected' => (clone $verified)->sum('amount_tzs'),
                'pending_verification' => (clone $pending)->sum('amount_tzs'),
                'pending_count' => (clone $pending)->count(),
                'verified_count' => (clone $verified)->count(),
                'outstanding_rooms' => $outstanding,
            ],
            'filters' => $request->only(['room_number', 'status', 'resident', 'payment_date']),
        ]);
    }

    public function create(Request $request)
    {
        return view('electricity.create', [
            'user' => $request->user(),
            'activePeriod' => ElectricityPeriod::query()->where('is_active', true)->latest()->first(),
            'methods' => ['Mobile Money', 'Bank', 'Cash', 'Other'],
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $activePeriod = ElectricityPeriod::query()->where('is_active', true)->latest()->first();

        $validated = $request->validate([
            'amount_tzs' => ['required', 'numeric', 'min:1'],
            'payment_date' => ['required', 'date', 'before_or_equal:today'],
            'payment_method' => ['required', Rule::in(['Mobile Money', 'Bank', 'Cash', 'Other'])],
            'transaction_reference' => ['nullable', 'string', 'max:120'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:4096'],
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $payment = ElectricityPayment::create([
            'user_id' => $user->id,
            'room_number' => $user->room_number,
            'electricity_period_id' => $activePeriod?->id,
            'amount_tzs' => $validated['amount_tzs'],
            'payment_date' => $validated['payment_date'],
            'payment_method' => $validated['payment_method'],
            'transaction_reference' => $validated['transaction_reference'] ?? null,
            'receipt_path' => $receiptPath,
            'status' => PaymentStatus::Pending,
        ]);

        PortalNotifier::notifyAdmins(
            'electricity_payment',
            'New electricity payment submitted',
            "{$user->name} (Room {$user->room_number}) submitted TZS ".number_format((float) $payment->amount_tzs, 2).'.',
            ['payment_id' => $payment->id],
        );

        PortalNotifier::log($user, 'electricity_payment', "Payment submitted for Room {$user->room_number}.");

        return redirect()->route('electricity.index')->with('status', 'Payment recorded. Status: Pending Verification.');
    }
}
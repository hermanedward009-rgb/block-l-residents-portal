@extends('layouts.app')
@section('heading', 'Electricity Payments')
@section('content')
<div class="mb-6 grid gap-4 sm:grid-cols-4">
    <div class="stat-card"><p class="text-xs text-slate-500">Total collected</p><p class="text-xl font-bold text-emerald-700">TZS {{ number_format($summary['total_collected'], 2) }}</p></div>
    <div class="stat-card"><p class="text-xs text-slate-500">Pending verification</p><p class="text-xl font-bold text-amber-700">TZS {{ number_format($summary['pending_verification'], 2) }}</p></div>
    <div class="stat-card"><p class="text-xs text-slate-500">Verified count</p><p class="text-xl font-bold">{{ $summary['verified_count'] }}</p></div>
    <div class="stat-card"><p class="text-xs text-slate-500">Outstanding rooms</p><p class="text-xl font-bold">{{ $activePeriod ? $summary['outstanding_rooms']->count() : '—' }}</p></div>
</div>
@if(!$activePeriod)
    <p class="mb-4 rounded-xl bg-amber-50 px-4 py-3 text-sm text-amber-800">No active electricity contribution period. Unpaid rooms are only listed after the admin sets an amount and deadline.</p>
@endif
<div class="mb-4 flex flex-wrap gap-2">
    @unless(auth()->user()->isAdmin())
        <a href="{{ route('electricity.create') }}" class="btn-primary">Record payment</a>
    @endunless
</div>
<form method="GET" class="mb-4 grid gap-3 rounded-2xl border border-slate-200 bg-white p-4 sm:grid-cols-5">
    <input type="number" name="room_number" min="1" max="10" value="{{ $filters['room_number'] ?? '' }}" placeholder="Room" class="form-input">
    <input type="text" name="resident" value="{{ $filters['resident'] ?? '' }}" placeholder="Resident name" class="form-input">
    <input type="date" name="payment_date" value="{{ $filters['payment_date'] ?? '' }}" class="form-input">
    <select name="status" class="form-input">
        <option value="">All statuses</option>
        @foreach (['pending','verified','rejected'] as $s)
            <option value="{{ $s }}" @selected(($filters['status'] ?? '') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
    </select>
    <button class="btn-secondary">Filter</button>
</form>
<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-left text-xs uppercase text-slate-500">
            <tr>
                <th class="px-4 py-3">Resident</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Amount</th><th class="px-4 py-3">Date</th><th class="px-4 py-3">Method</th><th class="px-4 py-3">Reference</th><th class="px-4 py-3">Status</th>
                @if(auth()->user()->isAdmin())<th class="px-4 py-3">Actions</th>@endif
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse ($payments as $payment)
                <tr>
                    <td class="px-4 py-3">{{ $payment->user->name }}</td>
                    <td class="px-4 py-3">Room {{ $payment->room_number }}</td>
                    <td class="px-4 py-3">TZS {{ number_format($payment->amount_tzs, 2) }}</td>
                    <td class="px-4 py-3">{{ $payment->payment_date->format('M j, Y') }}</td>
                    <td class="px-4 py-3">{{ $payment->payment_method }}</td>
                    <td class="px-4 py-3">{{ $payment->transaction_reference ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $payment->status->label() }}</td>
                    @if(auth()->user()->isAdmin())
                        <td class="px-4 py-3">
                            @if($payment->status->value === 'pending')
                                <form method="POST" action="{{ route('admin.electricity.verify', $payment) }}" class="inline">@csrf<button class="text-emerald-600 hover:underline">Verify</button></form>
                                <details class="inline ml-2">
                                    <summary class="cursor-pointer text-red-600">Reject</summary>
                                    <form method="POST" action="{{ route('admin.electricity.reject', $payment) }}" class="mt-2 w-48">@csrf
                                        <textarea name="rejection_reason" required class="form-input text-xs" placeholder="Reason"></textarea>
                                        <button class="mt-1 text-xs text-red-700">Confirm</button>
                                    </form>
                                </details>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-slate-500">No payment records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $payments->links() }}</div>
@endsection
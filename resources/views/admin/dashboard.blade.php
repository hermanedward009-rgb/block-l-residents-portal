@extends('layouts.app')
@section('heading', 'Administrator Dashboard')
@section('content')
<div class="grid gap-4 sm:grid-cols-3">
    <div class="stat-card"><p class="text-xs text-slate-500">Pending registrations</p><p class="text-3xl font-bold text-amber-600">{{ $pendingUsers }}</p></div>
    <div class="stat-card"><p class="text-xs text-slate-500">Approved residents</p><p class="text-3xl font-bold text-navy-900">{{ $approvedResidents }}</p></div>
    <div class="stat-card"><p class="text-xs text-slate-500">Payments to verify</p><p class="text-3xl font-bold text-emerald-700">{{ $pendingPayments }}</p></div>
</div>
<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <h3 class="font-semibold">Pending approvals</h3>
        <ul class="mt-3 divide-y divide-slate-100 text-sm">
            @forelse($pendingRegistrations as $user)
                <li class="flex items-center justify-between py-3">
                    <span>{{ $user->name }} · Room {{ $user->room_number }}</span>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">@csrf<button class="text-emerald-600 text-xs font-semibold">Approve</button></form>
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">@csrf<button class="text-red-600 text-xs font-semibold">Reject</button></form>
                    </div>
                </li>
            @empty
                <li class="py-4 text-slate-500">No pending registrations.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.users.index') }}" class="mt-3 inline-block text-sm font-semibold text-navy-800">Manage all residents →</a>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-5">
        <h3 class="font-semibold">Pending electricity verifications</h3>
        <ul class="mt-3 divide-y divide-slate-100 text-sm">
            @forelse($pendingPaymentRows as $payment)
                <li class="py-3">{{ $payment->user->name }} — TZS {{ number_format($payment->amount_tzs,2) }}</li>
            @empty
                <li class="py-4 text-slate-500">No pending payments.</li>
            @endforelse
        </ul>
        <a href="{{ route('electricity.index') }}" class="mt-3 inline-block text-sm font-semibold text-navy-800">Open electricity records →</a>
    </section>
</div>
<section class="mt-8 rounded-2xl border border-slate-200 bg-white p-5">
    <h3 class="font-semibold">Activity log</h3>
    <ul class="mt-3 space-y-1 text-xs text-slate-600">
        @foreach($recentLogs as $log)
            <li>{{ $log->description }} · {{ $log->created_at->diffForHumans() }}</li>
        @endforeach
    </ul>
</section>
@endsection
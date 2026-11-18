@extends('layouts.app')
@section('heading', __('portal.admin_dashboard'))
@section('content')
<div class="mb-6 flex flex-wrap gap-2">
    <a href="{{ route('admin.users.index') }}" class="quick-action-chip"><span aria-hidden="true">👥</span> {{ __('portal.residents_rooms') }}</a>
    <a href="{{ route('admin.announcements.index') }}" class="quick-action-chip"><span aria-hidden="true">📢</span> {{ __('portal.announcements') }}</a>
    <a href="{{ route('admin.cleaning.index') }}" class="quick-action-chip"><span aria-hidden="true">🚽</span> {{ __('portal.toilet_rota') }}</a>
    <a href="{{ route('admin.electricity.periods') }}" class="quick-action-chip"><span aria-hidden="true">⚡</span> {{ __('portal.contribution_settings') }}</a>
    <a href="{{ route('admin.reports.index') }}" class="quick-action-chip"><span aria-hidden="true">📊</span> {{ __('portal.reports') }}</a>
</div>

<div class="grid gap-4 sm:grid-cols-3">
    <div class="stat-card-glow stat-card-amber">
        <p class="text-xs font-semibold uppercase tracking-wider text-amber-100">{{ __('portal.pending_approvals') }}</p>
        <p class="mt-2 text-4xl font-bold">{{ $pendingUsers }}</p>
    </div>
    <div class="stat-card-glow stat-card-navy">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-300">{{ __('portal.approved_residents') }}</p>
        <p class="mt-2 text-4xl font-bold">{{ $approvedResidents }}</p>
    </div>
    <div class="stat-card-glow stat-card-emerald">
        <p class="text-xs font-semibold uppercase tracking-wider text-emerald-100">{{ __('portal.pending_payments') }}</p>
        <p class="mt-2 text-4xl font-bold">{{ $pendingPayments }}</p>
    </div>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="dashboard-panel">
        <div class="dashboard-panel-header">
            <h3 class="dashboard-panel-title"><span class="text-lg" aria-hidden="true">⏳</span> {{ __('portal.pending_approvals') }}</h3>
        </div>
        <ul class="mt-2 divide-y divide-slate-100 text-sm">
            @forelse($pendingRegistrations as $user)
                <li class="flex flex-wrap items-center justify-between gap-2 py-3">
                    <div>
                        <p class="font-semibold text-navy-900">{{ $user->name }}</p>
                        <p class="text-xs text-slate-500">Room {{ $user->room_number }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.users.approve', $user) }}">@csrf<button class="rounded-lg bg-emerald-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-emerald-700">{{ __('portal.approve') }}</button></form>
                        <form method="POST" action="{{ route('admin.users.reject', $user) }}">@csrf<button class="rounded-lg bg-red-50 px-3 py-1.5 text-xs font-bold text-red-700 hover:bg-red-100">{{ __('portal.reject') }}</button></form>
                    </div>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-slate-500">No pending registrations.</li>
            @endforelse
        </ul>
        <a href="{{ route('admin.users.index') }}" class="portal-text-link mt-4 text-sm">{{ __('portal.manage_residents') }} →</a>
    </section>

    <section class="dashboard-panel">
        <div class="dashboard-panel-header">
            <h3 class="dashboard-panel-title"><span class="text-lg" aria-hidden="true">💡</span> {{ __('portal.pending_payments') }}</h3>
        </div>
        <ul class="mt-2 divide-y divide-slate-100 text-sm">
            @forelse($pendingPaymentRows as $payment)
                <li class="flex items-center justify-between py-3">
                    <span class="font-medium text-navy-900">{{ $payment->user->name }}</span>
                    <span class="rounded-lg bg-amber-50 px-2 py-1 text-xs font-bold text-amber-800">TZS {{ number_format($payment->amount_tzs, 2) }}</span>
                </li>
            @empty
                <li class="py-6 text-center text-sm text-slate-500">No pending payments.</li>
            @endforelse
        </ul>
        <a href="{{ route('electricity.index') }}" class="portal-text-link mt-4 text-sm">{{ __('portal.open_electricity') }} →</a>
    </section>
</div>

<section class="dashboard-panel mt-8">
    <div class="dashboard-panel-header">
        <h3 class="dashboard-panel-title"><span class="text-lg" aria-hidden="true">📜</span> {{ __('portal.activity_log') }}</h3>
        <form method="POST" action="{{ route('admin.activity.clear') }}" onsubmit="return confirm(@js(__('portal.clear_activity_log').'?');">
            @csrf
            <button type="submit" class="btn-secondary text-xs">{{ __('portal.clear_activity_log') }}</button>
        </form>
    </div>
    @include('partials.activity-timeline', ['logs' => $recentLogs, 'empty' => __('portal.no_activity')])
</section>
@endsection
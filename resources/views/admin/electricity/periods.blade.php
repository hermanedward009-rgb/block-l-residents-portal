@extends('layouts.app')
@section('heading', 'Electricity contribution settings')
@section('content')
<div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
    <h3 class="font-semibold text-navy-900">What the admin sets here</h3>
    <ul class="mt-2 list-inside list-disc space-y-1">
        <li><strong>Period title</strong> — e.g. “November 2026 Block L electricity”</li>
        <li><strong>Amount (TZS)</strong> — how much each room should contribute for that period</li>
        <li><strong>Period start / end</strong> — dates the bill covers (for records and reports)</li>
        <li><strong>Payment deadline</strong> (optional) — last day residents should pay; shown on the dashboard</li>
        <li><strong>Active period</strong> — only one period is “active” at a time; it drives suggested payment amounts and which rooms count as outstanding</li>
    </ul>
    <p class="mt-2 text-xs text-slate-500">Residents submit payments against the active period. The system does not mark anyone unpaid until an active period with an amount exists.</p>
</div>
@if($active)
    <p class="mb-4 rounded-xl bg-emerald-50 px-4 py-3 text-sm text-emerald-800">Active period: <strong>{{ $active->title }}</strong> — TZS {{ number_format($active->amount_tzs, 2) }}</p>
@endif
<form method="POST" action="{{ route('admin.electricity.periods.store') }}" class="mb-8 grid max-w-2xl gap-3 rounded-2xl border border-slate-200 bg-white p-5">
    @csrf
    <input name="title" placeholder="Period title" class="form-input" required>
    <input type="number" step="0.01" name="amount_tzs" placeholder="Amount per room (TZS)" class="form-input" required>
    <input type="date" name="period_start" class="form-input" required>
    <input type="date" name="period_end" class="form-input" required>
    <input type="date" name="payment_deadline" class="form-input">
    <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="is_active" value="1"> Set as active period</label>
    <button class="btn-primary w-fit">Save period</button>
</form>
<ul class="space-y-3">
    @foreach($periods as $period)
        <li class="flex items-center justify-between rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm">
            <span>{{ $period->title }} · TZS {{ number_format($period->amount_tzs,2) }} · {{ $period->period_start->format('M j') }} – {{ $period->period_end->format('M j, Y') }}</span>
            @unless($period->is_active)
                <form method="POST" action="{{ route('admin.electricity.periods.activate', $period) }}">@csrf<button class="text-xs font-semibold text-navy-800">Activate</button></form>
            @else
                <span class="text-xs font-semibold text-emerald-600">Active</span>
            @endunless
        </li>
    @endforeach
</ul>
@endsection
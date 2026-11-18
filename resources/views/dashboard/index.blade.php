@extends('layouts.app')
@section('heading', 'Dashboard')
@section('content')
<div class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-r from-navy-900 via-navy-800 to-navy-700 p-5 text-white shadow-lg">
    <p class="text-xs font-semibold uppercase tracking-widest text-emerald-300">Welcome back</p>
    <h3 class="mt-1 text-xl font-bold">{{ auth()->user()->name }}</h3>
    <p class="mt-2 text-sm text-slate-200">@if(auth()->user()->room_number) Room {{ auth()->user()->room_number }} · @endif Block L community portal</p>
</div>
@if(isset($todayDuties) && $todayDuties->isNotEmpty())
<section class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50/80 p-4">
    <h3 class="text-sm font-bold text-emerald-900">Today's toilet duty</h3>
    <div class="mt-3 grid gap-2 sm:grid-cols-2">
        @foreach($todayDuties as $duty)
            @php $isMine = $duty->isDutyFor(auth()->user()); @endphp
            <div class="rounded-xl bg-white px-3 py-2 text-sm shadow-sm {{ $isMine ? 'ring-2 ring-emerald-400' : '' }}">
                <span class="text-xs font-semibold {{ $duty->toilet_type->badgeClass() }} rounded-full px-2 py-0.5">{{ $duty->toilet_type->label() }}</span>
                @if($isMine)<span class="text-[10px] font-bold text-emerald-700"> · Your duty today</span>@endif
                <p class="mt-1 font-semibold text-navy-900">{{ $duty->assigneeLabelFor(auth()->user()) }}</p>
            </div>
        @endforeach
    </div>
</section>
@endif
<div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
    @foreach ([
        ['Total Rooms', $stats['total_rooms']],
        ['Registered Members', $stats['registered_members']],
        ['Verified Payments', $stats['electricity_verified']],
        ['Pending Payments', $stats['electricity_pending']],
        ['Toilet duties', $stats['upcoming_cleaning']],
        ['Announcements', $stats['announcements']],
    ] as [$label, $value])
        <div class="stat-card">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-navy-900">{{ $value }}</p>
        </div>
    @endforeach
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-2">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="font-semibold text-navy-900">Electricity summary</h3>
        @if ($activePeriod)
            <p class="mt-2 text-sm text-slate-600">Active period: <strong>{{ $activePeriod->title }}</strong> — TZS {{ number_format($activePeriod->amount_tzs, 2) }} due by {{ $activePeriod->payment_deadline?->format('M j, Y') ?? 'N/A' }}</p>
        @else
            <p class="mt-2 text-sm text-amber-700">No active contribution period set by admin yet. Outstanding balances are not calculated until then.</p>
        @endif
        <dl class="mt-4 grid grid-cols-2 gap-3 text-sm">
            <div class="rounded-xl bg-slate-50 p-3"><dt class="text-slate-500">Collected (verified)</dt><dd class="font-semibold text-emerald-700">TZS {{ number_format($totalCollected, 2) }}</dd></div>
            <div class="rounded-xl bg-slate-50 p-3"><dt class="text-slate-500">Pending verification</dt><dd class="font-semibold text-amber-700">TZS {{ number_format($pendingAmount, 2) }}</dd></div>
        </dl>
        <a href="{{ route('electricity.create') }}" class="btn-primary mt-4">Submit payment</a>
    </section>

    <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
        <h3 class="font-semibold text-navy-900">Upcoming cleaning</h3>
        <ul class="mt-3 space-y-2 text-sm">
            @forelse ($upcomingCleaning as $item)
                <li class="flex justify-between rounded-lg bg-slate-50 px-3 py-2">
                    <span>{{ $item->room->label }} — {{ \App\Models\CleaningSchedule::dayName($item->day_of_week) }}</span>
                    <span class="text-slate-500">{{ $item->scheduled_date?->format('M j') ?? 'Weekly' }}</span>
                </li>
            @empty
                <li class="text-slate-500">No schedule entries yet.</li>
            @endforelse
        </ul>
        <a href="{{ route('cleaning.index') }}" class="mt-4 inline-block text-sm font-semibold text-navy-800">View timetable →</a>
    </section>
</div>

<div class="mt-8 grid gap-6 lg:grid-cols-3">
    <section class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-1">
        <h3 class="font-semibold">Recent announcements</h3>
        <ul class="mt-3 space-y-3 text-sm">
            @forelse ($announcements as $note)
                <li><p class="font-medium">{{ $note->title }}</p><p class="text-xs text-slate-500">{{ $note->created_at->diffForHumans() }}</p></li>
            @empty
                <li class="text-slate-500">No announcements yet.</li>
            @endforelse
        </ul>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-1">
        <h3 class="font-semibold">Latest advice</h3>
        <ul class="mt-3 space-y-3 text-sm">
            @forelse ($latestAdvice as $post)
                <li><p class="font-medium">{{ $post->subject }}</p><p class="text-xs text-slate-500">Room {{ $post->user->room_number }} · {{ $post->created_at->diffForHumans() }}</p></li>
            @empty
                <li class="text-slate-500">No posts yet.</li>
            @endforelse
        </ul>
    </section>
    <section class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-1">
        <h3 class="font-semibold">Recent activity</h3>
        <ul class="mt-3 space-y-2 text-xs text-slate-600">
            @forelse ($recentActivities as $log)
                <li>{{ $log->description }} <span class="text-slate-400">· {{ $log->created_at->diffForHumans() }}</span></li>
            @empty
                <li>No activity logged.</li>
            @endforelse
        </ul>
    </section>
</div>
@endsection
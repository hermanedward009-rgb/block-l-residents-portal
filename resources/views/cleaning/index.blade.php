@extends('layouts.app')
@section('heading', 'Toilet cleaning rota')
@section('content')
<p class="mb-4 text-sm text-slate-600">Each day, <strong>two residents</strong> are on duty (by room): one for the <span class="rounded bg-sky-100 px-1.5 text-sky-800">small toilet</span>, one for the <span class="rounded bg-violet-100 px-1.5 text-violet-800">large toilet</span>.</p>

@if($user->room_number && $myDuties->isNotEmpty())
    <section class="mb-6 rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wide text-emerald-800">Your duty — {{ $user->name }}</h3>
        <p class="mt-1 text-xs text-slate-600">Based on your room login, this is when <strong>you</strong> clean:</p>
        <ul class="mt-3 space-y-2">
            @foreach($myDuties as $duty)
                <li class="flex flex-wrap items-center gap-2 rounded-xl bg-white px-3 py-2 text-sm shadow-sm">
                    <span class="font-semibold text-navy-900">{{ \App\Models\CleaningSchedule::dayName($duty->day_of_week) }}</span>
                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $duty->toilet_type->badgeClass() }}">{{ $duty->toilet_type->label() }}</span>
                    <span class="text-emerald-700">You ({{ $user->name }})</span>
                </li>
            @endforeach
        </ul>
    </section>
@elseif($user->room_number)
    <p class="mb-4 rounded-xl bg-slate-100 px-4 py-3 text-sm text-slate-600">No toilet duties are assigned to your room yet. The admin will add you to the rota.</p>
@endif

<div class="space-y-4">
    @php $hasRows = false; @endphp
    @foreach($schedules as $day => $rows)
        @php $hasRows = true; @endphp
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm animate-fade-in">
            <div class="border-b border-slate-100 bg-navy-900 px-4 py-3 text-sm font-semibold text-white">{{ $day }}</div>
            <div class="grid gap-3 p-4 sm:grid-cols-2">
                @foreach($rows->sortBy('toilet_type') as $schedule)
                    @php $isMine = $schedule->isDutyFor($user); @endphp
                    <div class="rounded-xl border p-4 transition hover:shadow-md {{ $isMine ? 'border-emerald-300 bg-emerald-50/80' : 'border-slate-100 bg-slate-50' }}">
                        <span class="inline-block rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $schedule->toilet_type->badgeClass() }}">{{ $schedule->toilet_type->label() }}</span>
                        @if($isMine)
                            <span class="ml-1 rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">Your duty</span>
                        @endif
                        <div class="mt-2 flex items-center gap-3">
                            @php $assignees = $schedule->room->activeResidents ?? collect(); @endphp
                            @if($assignees->isNotEmpty())
                                @foreach($assignees as $resident)
                                    <img src="{{ $resident->avatarUrl() }}" alt="" class="h-10 w-10 rounded-full border-2 border-white shadow">
                                @endforeach
                            @endif
                            <p class="text-lg font-bold text-navy-900">{{ $schedule->assigneeLabelFor($user) }}</p>
                        </div>
                        <p class="text-xs text-slate-500">{{ $schedule->scheduled_date?->format('M j, Y') ?? 'Weekly duty' }}</p>
                        <p class="mt-2 text-sm {{ $schedule->is_completed ? 'text-emerald-600' : 'text-amber-700' }}">{{ $schedule->is_completed ? 'Completed' : 'Pending' }}</p>
                    </div>
                @endforeach
            </div>
        </section>
    @endforeach
    @unless($hasRows)
        <p class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">Toilet rota will appear once the administrator configures it.</p>
    @endunless
</div>
@endsection
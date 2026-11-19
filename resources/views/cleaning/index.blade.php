@extends('layouts.app')
@section('heading', __('portal.toilet_rota'))
@section('content')
<p class="mb-4 text-sm text-slate-600">
    Each day, two cleaning duties run in parallel:
    <span class="rounded bg-sky-100 px-1.5 font-semibold text-sky-800">{{ __('portal.toilet_small') }}</span>
    and
    <span class="rounded bg-violet-100 px-1.5 font-semibold text-violet-800">{{ __('portal.toilet_large') }}</span>.
    Duties are arranged by <strong>resident names</strong> (not room numbers). Roommates sharing a duty appear on separate lines.
</p>

@if($user->room_number && $myDuties->isNotEmpty())
    <section class="mb-6 rounded-2xl border-2 border-emerald-300 bg-gradient-to-br from-emerald-50 to-white p-5 shadow-sm">
        <h3 class="text-sm font-bold uppercase tracking-wide text-emerald-800">{{ __('portal.your_duty') }} — {{ $user->name }}</h3>
        <div class="mt-3 grid gap-3 sm:grid-cols-2">
            @foreach($myDuties->sortBy(fn ($d) => $d->toilet_type->value) as $duty)
                <div class="rounded-xl border border-white bg-white p-3 shadow-sm">
                    <span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $duty->toilet_type->badgeClass() }}">{{ $duty->toilet_type->label() }}</span>
                    <p class="mt-2 text-xs text-slate-500">{{ \App\Models\CleaningSchedule::dayName($duty->day_of_week) }}</p>
                    @include('partials.room-resident-list', [
                        'room' => $duty->room,
                        'highlightUser' => $user,
                        'compact' => true,
                        'listClass' => 'mt-2 text-sm font-semibold text-navy-900',
                    ])
                </div>
            @endforeach
        </div>
    </section>
@elseif($user->room_number)
    <p class="portal-callout-warning mb-4">No toilet duties are assigned to your room yet. The admin will add you to the rota.</p>
@endif

<div class="space-y-4">
    @php $hasRows = false; @endphp
    @foreach($schedules as $day => $rows)
        @php
            $hasRows = true;
            $smallDuty = $rows->first(fn ($s) => $s->toilet_type->value === 'small');
            $largeDuty = $rows->first(fn ($s) => $s->toilet_type->value === 'large');
        @endphp
        <section class="overflow-hidden portal-card shadow-sm animate-fade-in">
            <div class="border-b border-slate-100 bg-navy-900 px-4 py-3 text-sm font-semibold text-white">{{ $day }}</div>
            <div class="grid gap-3 p-4 sm:grid-cols-2">
                @include('partials.cleaning-duty-card', ['schedule' => $smallDuty, 'user' => $user, 'variant' => 'small'])
                @include('partials.cleaning-duty-card', ['schedule' => $largeDuty, 'user' => $user, 'variant' => 'large'])
            </div>
        </section>
    @endforeach
    @unless($hasRows)
        <p class="rounded-xl border border-dashed border-slate-300 bg-white p-8 text-center text-slate-500">Toilet rota will appear once the administrator configures it.</p>
    @endunless
</div>
@endsection
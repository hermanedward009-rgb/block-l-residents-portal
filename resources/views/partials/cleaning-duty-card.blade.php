@props(['schedule', 'user', 'variant' => 'small'])
@php
    $isMine = $schedule?->isDutyFor($user);
    $typeLabel = $variant === 'small' ? __('portal.toilet_small') : __('portal.toilet_large');
@endphp
<div class="rounded-xl border p-4 transition hover:shadow-md {{ $isMine ? 'border-emerald-300 bg-emerald-50/80' : 'border-slate-100 bg-white' }} {{ $schedule ? '' : 'border-dashed bg-slate-50/80 opacity-90' }}">
    <header class="flex flex-wrap items-center gap-2 border-b border-slate-100 pb-3">
        <span class="inline-flex items-center gap-1 rounded-full px-2.5 py-1 text-xs font-bold {{ $variant === 'small' ? 'bg-sky-100 text-sky-900' : 'bg-violet-100 text-violet-900' }}">
            {{ $typeLabel }}
        </span>
        @if($isMine)
            <span class="rounded-full bg-emerald-600 px-2 py-0.5 text-[10px] font-bold uppercase text-white">{{ __('portal.your_duty') }}</span>
        @endif
    </header>

    @if($schedule)
        <div class="mt-3">
            <div>
                @include('partials.room-resident-list', [
                    'room' => $schedule->room,
                    'highlightUser' => $user,
                    'prominent' => true,
                    'emptyText' => 'Awaiting resident assignment',
                ])
            </div>
            <p class="mt-3 text-xs text-slate-500">{{ $schedule->scheduled_date?->format('M j, Y') ?? 'Weekly duty' }}</p>
            <p class="mt-1 text-sm font-medium {{ $schedule->is_completed ? 'text-emerald-600' : 'text-amber-700' }}">
                {{ $schedule->is_completed ? __('portal.cleaning_done') : __('portal.cleaning_pending') }}
            </p>
        </div>
    @else
        <p class="mt-3 text-sm text-slate-500">{{ __('portal.no_toilet_duty_type', ['type' => $typeLabel]) }}</p>
    @endif
</div>
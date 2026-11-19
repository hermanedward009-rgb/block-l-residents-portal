@php
    $selectedUser = $schedule?->assigned_user_id ?? $schedule?->room?->activeResidents?->first()?->id;
@endphp
<div class="min-w-[9.5rem] space-y-1.5">
    @if($schedule)
        @include('partials.room-resident-list', [
            'room' => $schedule->room,
            'adminLinks' => true,
            'compact' => true,
            'listClass' => 'text-[11px] text-navy-900 leading-tight',
        ])
        <form method="POST" action="{{ route('admin.cleaning.update', $schedule) }}" class="space-y-1">
            @csrf @method('PUT')
            <select name="assigned_user_id" class="form-input w-full py-1 text-[11px]" required title="{{ __('portal.assign_resident') }}">
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}" @selected($selectedUser === $resident->id)>{{ $resident->name }}</option>
                @endforeach
            </select>
            <details class="text-[10px] text-slate-500">
                <summary class="cursor-pointer select-none hover:text-navy-900">More</summary>
                <div class="mt-1 space-y-1">
                    <select name="day_of_week" class="form-input w-full py-1 text-[11px]">
                        @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $short)
                            <option value="{{ $i }}" @selected($schedule->day_of_week === $i)>{{ $short }}</option>
                        @endforeach
                    </select>
                    <select name="toilet_type" class="form-input w-full py-1 text-[11px]">
                        <option value="small" @selected($schedule->toilet_type->value === 'small')>{{ __('portal.toilet_small') }}</option>
                        <option value="large" @selected($schedule->toilet_type->value === 'large')>{{ __('portal.toilet_large') }}</option>
                    </select>
                    <input type="date" name="scheduled_date" value="{{ $schedule->scheduled_date?->format('Y-m-d') }}" class="form-input w-full py-1 text-[11px]">
                    <input name="notes" value="{{ $schedule->notes }}" placeholder="Notes" class="form-input w-full py-1 text-[11px]">
                </div>
            </details>
            <button type="submit" class="btn-primary w-full py-1 text-[10px]">{{ __('portal.save_duty') }}</button>
        </form>
        <div class="flex flex-wrap gap-1">
            @unless($schedule->is_completed)
                <form method="POST" action="{{ route('admin.cleaning.complete', $schedule) }}" class="inline">@csrf<button type="submit" class="btn-inline-success py-0.5 text-[10px]">Done</button></form>
            @else
                <span class="text-[10px] font-semibold text-emerald-600">{{ __('portal.cleaning_done') }}</span>
            @endunless
            <form method="POST" action="{{ route('admin.cleaning.destroy', $schedule) }}" class="inline" onsubmit="return confirm('Remove?');">@csrf @method('DELETE')<button type="submit" class="btn-inline-danger py-0.5 text-[10px]">×</button></form>
        </div>
    @else
        <p class="text-[10px] text-slate-400">—</p>
        <form method="POST" action="{{ route('admin.cleaning.store') }}" class="space-y-1">
            @csrf
            <input type="hidden" name="day_of_week" value="{{ $dayIndex }}">
            <input type="hidden" name="toilet_type" value="{{ $type }}">
            <select name="assigned_user_id" class="form-input w-full py-1 text-[11px]" required>
                <option value="">{{ __('portal.select_resident') }}</option>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-secondary w-full py-1 text-[10px]">{{ __('portal.add_duty') }}</button>
        </form>
    @endif
</div>
@extends('layouts.app')
@section('heading', 'Toilet rota')
@section('content')
<div class="admin-rota-compact max-w-4xl">
    <details class="portal-card mb-3 p-3">
        <summary class="cursor-pointer text-xs font-semibold text-navy-900">{{ __('portal.add_duty') }} (any day)</summary>
        <form method="POST" action="{{ route('admin.cleaning.store') }}" class="mt-2 grid gap-2 sm:grid-cols-4">
            @csrf
            <select name="toilet_type" class="form-input py-1 text-xs" required>
                <option value="small">{{ __('portal.toilet_small') }}</option>
                <option value="large">{{ __('portal.toilet_large') }}</option>
            </select>
            <select name="assigned_user_id" class="form-input py-1 text-xs sm:col-span-2" required>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}">{{ $resident->name }}</option>
                @endforeach
            </select>
            <select name="day_of_week" class="form-input py-1 text-xs" required>
                @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $i => $short)
                    <option value="{{ $i }}">{{ $short }}</option>
                @endforeach
            </select>
            <button class="btn-primary py-1 text-xs sm:col-span-4 sm:w-fit">{{ __('portal.add_duty') }}</button>
        </form>
    </details>

    <p class="mb-2 text-[11px] leading-snug text-slate-600">
        {{ __('portal.cleaning_edit_help') }}
        <a href="{{ route('admin.users.index') }}" class="portal-text-link">{{ __('portal.residents_rooms') }}</a>
    </p>

    <div class="portal-card overflow-x-auto p-0">
        <table class="portal-table w-full min-w-[32rem] text-left text-xs">
            <thead>
                <tr class="bg-slate-50">
                    <th class="w-16 px-2 py-1.5 font-semibold text-slate-600">Day</th>
                    <th class="px-2 py-1.5 font-semibold text-sky-800">{{ __('portal.toilet_small') }}</th>
                    <th class="px-2 py-1.5 font-semibold text-violet-800">{{ __('portal.toilet_large') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $dayIndex => $dayName)
                    @php
                        $dayRows = $schedulesByDay[$dayIndex] ?? collect();
                        $small = $dayRows->first(fn ($s) => $s->toilet_type->value === 'small');
                        $large = $dayRows->first(fn ($s) => $s->toilet_type->value === 'large');
                    @endphp
                    <tr class="border-t border-slate-100 align-top">
                        <td class="px-2 py-2 font-semibold text-navy-900">{{ substr($dayName, 0, 3) }}</td>
                        <td class="px-2 py-2">
                            @include('admin.cleaning._duty-cell', [
                                'schedule' => $small,
                                'dayIndex' => $dayIndex,
                                'type' => 'small',
                                'residents' => $residents,
                            ])
                        </td>
                        <td class="px-2 py-2">
                            @include('admin.cleaning._duty-cell', [
                                'schedule' => $large,
                                'dayIndex' => $dayIndex,
                                'type' => 'large',
                                'residents' => $residents,
                            ])
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
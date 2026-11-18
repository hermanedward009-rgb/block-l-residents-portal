@extends('layouts.app')
@section('heading', 'Toilet rota (small & large)')
@section('content')
<form method="POST" action="{{ route('admin.cleaning.store') }}" class="mb-8 grid gap-3 portal-card p-5 sm:grid-cols-2 lg:max-w-3xl">
    @csrf
    <select name="room_id" class="form-input" required>@foreach($rooms as $room)<option value="{{ $room->id }}">{{ $room->label }}</option>@endforeach</select>
    <select name="toilet_type" class="form-input" required>
        <option value="small">Small toilet</option>
        <option value="large">Large toilet</option>
    </select>
    <select name="day_of_week" class="form-input" required>
        @foreach(['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'] as $i => $day)
            <option value="{{ $i }}">{{ $day }}</option>
        @endforeach
    </select>
    <input type="date" name="scheduled_date" class="form-input">
    <input name="notes" placeholder="Notes" class="form-input sm:col-span-2">
    <button class="btn-primary w-fit">Add duty</button>
</form>
<div class="portal-table-wrap">
    <table class="portal-table">
        <thead><tr><th>Toilet</th><th>Assigned resident(s)</th><th>Day</th><th>Done</th><th>Actions</th></tr></thead>
        <tbody>
            @foreach($schedules as $schedule)
                <tr>
                    <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs font-semibold {{ $schedule->toilet_type->badgeClass() }}">{{ $schedule->toilet_type->label() }}</span></td>
                    <td class="px-4 py-3">{{ $schedule->room->assigneeLabel() }}</td>
                    <td class="px-4 py-3">{{ \App\Models\CleaningSchedule::dayName($schedule->day_of_week) }}</td>
                    <td class="px-4 py-3">{{ $schedule->is_completed ? 'Yes' : 'No' }}</td>
                    <td class="px-4 py-3 text-xs space-x-2">
                        @unless($schedule->is_completed)
                            <form method="POST" action="{{ route('admin.cleaning.complete', $schedule) }}" class="inline">@csrf<button class="btn-inline-success">Complete</button></form>
                        @endunless
                        <form method="POST" action="{{ route('admin.cleaning.destroy', $schedule) }}" class="inline">@csrf @method('DELETE')<button class="btn-inline-danger">Remove</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
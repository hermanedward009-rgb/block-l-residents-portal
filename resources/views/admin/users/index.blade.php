@extends('layouts.app')
@section('heading', 'Residents & Rooms')
@section('content')
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-slate-600">Each room allows <strong>up to 2 members</strong>.</p>
    <form method="POST" action="{{ route('admin.residents.reset') }}" onsubmit="return confirm('Remove ALL resident accounts? Admin is kept.')">
        @csrf
        <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">Reset all residents</button>
    </form>
</div>
<h3 class="mb-4 font-semibold">All 10 rooms</h3>
<div class="mb-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
    @foreach($rooms as $room)
        @php $occ = \App\Services\RoomRegistrationGuard::roomOccupancy($room->number); @endphp
        <div class="rounded-xl border border-slate-200 bg-white p-3 text-sm shadow-sm">
            <form method="POST" action="{{ route('admin.rooms.label', $room) }}" class="space-y-2">@csrf @method('PUT')
                <label class="text-xs text-slate-500">Room name (shown instead of number)</label>
                <input name="label" value="{{ $room->label }}" class="form-input text-sm" required>
                <button type="submit" class="text-xs font-semibold text-navy-800">Save name</button>
            </form>
            @if($names = $room->residentNames())
                <p class="mt-2 text-xs text-slate-600">Residents: {{ implode(', ', $names) }}</p>
            @else
                <p class="mt-2 text-xs text-slate-400">No residents yet</p>
            @endif
            <p class="mt-1 text-xs text-slate-500">Slot #{{ $room->number }} · {{ $occ['count'] }}/{{ $occ['max'] }} members</p>
            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                <div class="h-full rounded-full bg-emerald-500 transition-all" style="width: {{ min(100, ($occ['count'] / max(1, $occ['max'])) * 100) }}%"></div>
            </div>
        </div>
    @endforeach
</div>
<form method="GET" class="mb-4 flex flex-wrap gap-2">
    <select name="status" class="form-input w-auto"><option value="">All statuses</option>@foreach(['pending','approved','rejected','deactivated'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select>
    <input type="number" name="room_number" min="1" max="10" value="{{ request('room_number') }}" placeholder="Room" class="form-input w-28">
    <button class="btn-secondary">Filter</button>
</form>
<div class="overflow-x-auto rounded-2xl border border-slate-200 bg-white">
    <table class="min-w-full text-sm">
        <thead class="bg-slate-50 text-xs uppercase text-slate-500"><tr><th class="px-4 py-3">Name</th><th class="px-4 py-3">Room</th><th class="px-4 py-3">Status</th><th class="px-4 py-3">Actions</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
            @foreach($users as $user)
                @continue($user->isAdmin())
                <tr>
                    <td class="px-4 py-3">{{ $user->name }}<br><span class="text-xs text-slate-500">{{ $user->email }}</span></td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.users.room', $user) }}" class="flex gap-1">@csrf @method('PUT')
                            <select name="room_number" class="form-input w-24 text-xs">
                                @foreach(range(1,10) as $r)<option value="{{ $r }}" @selected($user->room_number==$r)>{{ $r }}</option>@endforeach
                            </select>
                            <button class="text-xs text-navy-800">Save</button>
                        </form>
                    </td>
                    <td class="px-4 py-3"><span class="rounded-full px-2 py-0.5 text-xs {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span></td>
                    <td class="px-4 py-3 space-x-2 text-xs">
                        @if($user->status->value==='pending')
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">@csrf<button class="text-emerald-600">Approve</button></form>
                            <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">@csrf<button class="text-red-600">Reject</button></form>
                        @elseif($user->status->value==='approved')
                            <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">@csrf<button class="text-slate-600">Deactivate</button></form>
                        @elseif($user->status->value==='deactivated')
                            <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="inline">@csrf<button class="text-emerald-600">Reactivate</button></form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $users->links() }}
@endsection
@extends('layouts.app')
@section('heading', 'Residents & Rooms')
@section('content')
<div class="mb-6 grid gap-3 sm:grid-cols-3">
    <div class="stat-card text-center"><p class="text-xs text-slate-500">{{ __('portal.all_accounts') }}</p><p class="text-2xl font-bold text-navy-900">{{ $accountCounts['total'] }}</p></div>
    <div class="stat-card text-center"><p class="text-xs text-slate-500">{{ __('portal.administrator') }}</p><p class="text-2xl font-bold text-navy-900">{{ $accountCounts['admins'] }}</p></div>
    <div class="stat-card text-center"><p class="text-xs text-slate-500">{{ __('portal.resident') }}s</p><p class="text-2xl font-bold text-navy-900">{{ $accountCounts['residents'] }}</p></div>
</div>
<div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <p class="text-sm text-slate-600">Each room allows <strong>up to 2 members</strong>. Open <strong>{{ __('portal.manage_account') }}</strong> to change room or password.</p>
    <form method="POST" action="{{ route('admin.residents.reset') }}" onsubmit="return confirm('Remove ALL resident accounts? Admin is kept.');">
        @csrf
        <button type="submit" class="btn-danger">Reset all residents</button>
    </form>
</div>
<h3 class="mb-4 font-semibold">All 10 rooms</h3>
<div class="mb-8 grid gap-3 sm:grid-cols-2 lg:grid-cols-5">
    @foreach($rooms as $room)
        @php $occ = \App\Services\RoomRegistrationGuard::roomOccupancy($room->number); @endphp
        <div class="portal-tile">
            <form method="POST" action="{{ route('admin.rooms.label', $room) }}" class="space-y-2">@csrf @method('PUT')
                <label class="text-xs text-slate-500">Room name (shown instead of number)</label>
                <input name="label" value="{{ $room->label }}" class="form-input text-sm" required>
                <button type="submit" class="btn-inline-neutral">Save name</button>
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
    <select name="role" class="form-input w-auto">
        <option value="">{{ __('portal.role') }}: All</option>
        <option value="admin" @selected(request('role')==='admin')>{{ __('portal.administrator') }}</option>
        <option value="resident" @selected(request('role')==='resident')>{{ __('portal.resident') }}</option>
    </select>
    <select name="status" class="form-input w-auto"><option value="">All statuses</option>@foreach(['pending','approved','rejected','deactivated'] as $s)<option value="{{ $s }}" @selected(request('status')===$s)>{{ ucfirst($s) }}</option>@endforeach</select>
    <input type="number" name="room_number" min="1" max="10" value="{{ request('room_number') }}" placeholder="Room" class="form-input w-28">
    <button class="btn-secondary">Filter</button>
</form>
<div class="portal-table-wrap">
    <table class="portal-table">
        <thead>
            <tr>
                <th>Account</th>
                <th>{{ __('portal.role') }}</th>
                <th>Room</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>
                        <div class="flex items-center gap-3">
                            @include('partials.avatar', ['user' => $user, 'size' => 'xs', 'class' => 'ring-2 ring-slate-100'])
                            <div>
                                <p class="font-semibold text-navy-900">{{ $user->name }}</p>
                                <p class="text-xs text-slate-500">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="portal-chip {{ $user->isAdmin() ? 'bg-navy-100 text-navy-900' : '' }}">
                            {{ $user->isAdmin() ? __('portal.administrator') : __('portal.resident') }}
                        </span>
                    </td>
                    <td>
                        @if($user->isAdmin())
                            <span class="text-slate-400">—</span>
                        @else
                            <form method="POST" action="{{ route('admin.users.room', $user) }}" class="flex flex-wrap items-center gap-1">@csrf @method('PUT')
                                <select name="room_number" class="form-input w-24 text-xs">
                                    @foreach(range(1, 10) as $r)<option value="{{ $r }}" @selected($user->room_number==$r)>{{ $r }}</option>@endforeach
                                </select>
                                <button type="submit" class="btn-inline-neutral">Save</button>
                            </form>
                        @endif
                    </td>
                    <td><span class="rounded-full px-2 py-0.5 text-xs {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span></td>
                    <td class="space-x-1 whitespace-nowrap text-xs">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn-inline-neutral">{{ __('portal.manage_account') }}</a>
                        @unless($user->isAdmin())
                            @if($user->status->value==='pending')
                                <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="inline">@csrf<button class="btn-inline-success">{{ __('portal.approve') }}</button></form>
                                <form method="POST" action="{{ route('admin.users.reject', $user) }}" class="inline">@csrf<button class="btn-inline-danger">{{ __('portal.reject') }}</button></form>
                            @elseif($user->status->value==='approved')
                                <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">@csrf<button class="btn-inline-neutral">Deactivate</button></form>
                            @elseif($user->status->value==='deactivated')
                                <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="inline">@csrf<button class="btn-inline-success">Reactivate</button></form>
                            @endif
                        @endunless
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="py-8 text-center text-slate-500">No accounts match this filter.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
{{ $users->links() }}
@endsection
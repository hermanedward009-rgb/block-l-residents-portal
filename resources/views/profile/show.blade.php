@extends('layouts.app')
@section('heading', 'My Profile')
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-6 lg:col-span-1">
        <img src="{{ $user->avatarUrl() }}" alt="" class="mx-auto h-28 w-28 rounded-full border-4 border-navy-100 object-cover">
        <h3 class="mt-4 text-center text-lg font-semibold">{{ $user->name }}</h3>
        <p class="text-center text-sm text-slate-500">Room {{ $user->room_number }}</p>
        <p class="mt-4 text-center"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span></p>
        <dl class="mt-6 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Phone</dt><dd>{{ $user->phone }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Joined</dt><dd>{{ $user->created_at->format('M j, Y') }}</dd></div>
        </dl>
    </div>
    <div class="lg:col-span-2 rounded-2xl border border-slate-200 bg-white p-6">
        <h3 class="font-semibold text-navy-900">Edit information</h3>
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
            @csrf @method('PUT')
            <div class="grid gap-4 sm:grid-cols-2">
                <div><label class="text-sm font-medium">Full name</label><input name="name" value="{{ old('name', $user->name) }}" class="form-input mt-1" required></div>
                <div><label class="text-sm font-medium">Phone</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1" required></div>
                <div class="sm:col-span-2"><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1" required></div>
                <div class="sm:col-span-2"><label class="text-sm font-medium">Profile picture</label><input type="file" name="profile_picture" accept="image/*" class="form-input mt-1"></div>
            </div>
            <button class="btn-primary">Save changes</button>
        </form>
        <h4 class="mt-8 font-semibold">My cleaning assignments</h4>
        <ul class="mt-2 space-y-1 text-sm text-slate-600">
            @forelse ($cleaning as $row)
                <li><strong>{{ $user->name }}</strong> — {{ \App\Models\CleaningSchedule::dayName($row->day_of_week) }} · {{ $row->toilet_type?->label() ?? 'Duty' }}</li>
            @empty
                <li>No assignments for your room yet.</li>
            @endforelse
        </ul>
    </div>
</div>
@endsection
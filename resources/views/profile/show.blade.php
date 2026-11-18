@extends('layouts.app')
@section('heading', 'My Profile')
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="portal-card p-6 lg:col-span-1">
        <div class="mx-auto w-fit">@include('partials.avatar', ['user' => $user, 'size' => 'lg'])</div>
        <h3 class="mt-4 text-center text-lg font-semibold">{{ $user->name }}</h3>
        <p class="text-center text-sm text-slate-500">
            @if($user->room_number)
                Room {{ $user->room_number }}
            @elseif($user->isAdmin())
                {{ __('portal.administrator') }}
            @endif
        </p>
        <p class="mt-4 text-center"><span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span></p>
        <dl class="mt-6 space-y-2 text-sm">
            <div class="flex justify-between"><dt class="text-slate-500">Email</dt><dd>{{ $user->email }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Phone</dt><dd>{{ $user->phone }}</dd></div>
            <div class="flex justify-between"><dt class="text-slate-500">Joined</dt><dd>{{ $user->created_at->format('M j, Y') }}</dd></div>
        </dl>
    </div>
    <div class="space-y-6 lg:col-span-2">
        <div class="portal-card p-6">
            <h3 class="dashboard-panel-title">Edit information</h3>
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div><label class="text-sm font-medium">Full name</label><input name="name" value="{{ old('name', $user->name) }}" class="form-input mt-1" required></div>
                    <div><label class="text-sm font-medium">Phone</label><input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1" required></div>
                    <div class="sm:col-span-2"><label class="text-sm font-medium">Email</label><input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1" required></div>
                    <div class="sm:col-span-2">
                        <label class="text-sm font-medium">Profile picture</label>
                        <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" class="form-input mt-1">
                        <p class="mt-1 text-xs text-slate-500">{{ __('portal.upload_hint') }}</p>
                        @error('profile_picture')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button class="btn-primary">Save changes</button>
            </form>
        </div>

        <div class="portal-card p-6">
            <h3 class="dashboard-panel-title">{{ __('portal.change_password') }}</h3>
            <form method="POST" action="{{ route('profile.password') }}" class="mt-4 grid max-w-md gap-3">
                @csrf @method('PUT')
                <div>
                    <label class="text-sm font-medium">{{ __('portal.current_password') }}</label>
                    <input type="password" name="current_password" class="form-input mt-1" required autocomplete="current-password">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('portal.new_password') }}</label>
                    <input type="password" name="password" class="form-input mt-1" required autocomplete="new-password">
                </div>
                <div>
                    <label class="text-sm font-medium">{{ __('portal.confirm_new_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-input mt-1" required autocomplete="new-password">
                </div>
                @error('current_password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                <button type="submit" class="btn-secondary w-fit">{{ __('portal.change_password') }}</button>
            </form>
        </div>

        @if($user->room_number)
            <div class="portal-card p-6">
                <h4 class="font-semibold">My cleaning assignments</h4>
                <ul class="mt-2 space-y-1 text-sm text-slate-600">
                    @forelse ($cleaning as $row)
                        <li class="portal-list-row"><strong>{{ $user->name }}</strong> — {{ \App\Models\CleaningSchedule::dayName($row->day_of_week) }} · {{ $row->toilet_type?->label() ?? 'Duty' }}</li>
                    @empty
                        <li>No assignments for your room yet.</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
</div>
@endsection
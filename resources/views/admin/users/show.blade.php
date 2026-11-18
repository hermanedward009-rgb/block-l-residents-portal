@extends('layouts.app')
@section('heading', $user->name)
@section('content')
<div class="mb-4">
    <a href="{{ route('admin.users.index') }}" class="portal-text-link text-sm">← {{ __('portal.residents_rooms') }}</a>
</div>
<div class="grid gap-6 lg:grid-cols-3">
    <div class="portal-card p-6 lg:col-span-1">
        <div class="mx-auto w-fit">@include('partials.avatar', ['user' => $user, 'size' => 'md'])</div>
        <h3 class="mt-4 text-center text-lg font-bold text-navy-900">{{ $user->name }}</h3>
        <p class="text-center text-sm text-slate-500">
            @if($user->isAdmin())
                {{ __('portal.administrator') }}
            @else
                Room {{ $user->room_number ?? '—' }} · {{ __('portal.resident') }}
            @endif
        </p>
        <p class="mt-3 text-center">
            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $user->status->badgeClass() }}">{{ $user->status->label() }}</span>
        </p>
        <dl class="mt-6 space-y-2 text-sm">
            <div class="flex justify-between gap-2"><dt class="text-slate-500">{{ __('portal.email') }}</dt><dd class="text-right">{{ $user->email }}</dd></div>
            <div class="flex justify-between gap-2"><dt class="text-slate-500">{{ __('portal.phone') }}</dt><dd>{{ $user->phone }}</dd></div>
            <div class="flex justify-between gap-2"><dt class="text-slate-500">Joined</dt><dd>{{ $user->created_at->format('M j, Y') }}</dd></div>
        </dl>
    </div>

    <div class="space-y-6 lg:col-span-2">
        <section class="dashboard-panel">
            <h3 class="dashboard-panel-title">{{ __('portal.manage_account') }}</h3>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="mt-4 space-y-4">
                @csrf @method('PUT')
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="guest-field-label">{{ __('portal.full_name') }}</label>
                        <input name="name" value="{{ old('name', $user->name) }}" class="form-input mt-1" required>
                    </div>
                    <div>
                        <label class="guest-field-label">{{ __('portal.phone') }}</label>
                        <input name="phone" value="{{ old('phone', $user->phone) }}" class="form-input mt-1" required>
                    </div>
                    <div class="sm:col-span-2">
                        <label class="guest-field-label">{{ __('portal.email') }}</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-input mt-1" required>
                    </div>
                    @unless($user->isAdmin())
                        <div class="sm:col-span-2">
                            <label class="guest-field-label">{{ __('portal.room_number') }}</label>
                            <select name="room_number" class="form-input mt-1" required>
                                @foreach($rooms as $room)
                                    <option value="{{ $room->number }}" @selected(old('room_number', $user->room_number) == $room->number)>{{ $room->displayName() }}</option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-xs text-slate-500">Move member to another room (max 2 per room).</p>
                        </div>
                    @endunless
                    <div class="sm:col-span-2">
                        <label class="guest-field-label">{{ __('portal.profile_picture_optional') }}</label>
                        <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" class="form-input mt-1">
                        <p class="mt-1 text-xs text-slate-500">{{ __('portal.upload_hint') }}</p>
                        @error('profile_picture')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <button type="submit" class="btn-primary">Save account</button>
            </form>
        </section>

        <section class="dashboard-panel">
            <h3 class="dashboard-panel-title">{{ __('portal.reset_member_password') }}</h3>
            <form method="POST" action="{{ route('admin.users.password', $user) }}" class="mt-4 grid max-w-md gap-3">
                @csrf @method('PUT')
                <div>
                    <label class="guest-field-label">{{ __('portal.new_password') }}</label>
                    <input type="password" name="password" class="form-input mt-1" required autocomplete="new-password">
                </div>
                <div>
                    <label class="guest-field-label">{{ __('portal.confirm_new_password') }}</label>
                    <input type="password" name="password_confirmation" class="form-input mt-1" required autocomplete="new-password">
                </div>
                @error('password')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
                <button type="submit" class="btn-secondary w-fit">{{ __('portal.change_password') }}</button>
            </form>
        </section>
    </div>
</div>
@endsection
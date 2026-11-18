@extends('layouts.guest')
@section('title', __('portal.register').' — Block L')
@section('content')
<header class="text-center sm:text-left">
    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-emerald-600 to-navy-800 text-2xl shadow-lg shadow-emerald-900/20 sm:mx-0" aria-hidden="true">
        🏠
    </div>
    <h2 class="text-2xl font-bold tracking-tight text-navy-900 sm:text-[1.75rem]">{{ __('portal.join_block_l') }}</h2>
    <div class="guest-heading-accent mx-auto sm:mx-0" aria-hidden="true"></div>
    <p class="mt-4 text-sm text-slate-500">{{ __('portal.approval_note') }}</p>
</header>
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mt-8 space-y-4">
    @csrf
    <div class="space-y-2">
        <label class="guest-field-label">{{ __('portal.full_name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="form-input form-input-lg" placeholder="Herman Mkumbwa">
    </div>
    <div class="space-y-2">
        <label class="guest-field-label">{{ __('portal.email') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="form-input form-input-lg" placeholder="you@example.com">
    </div>
    <div class="space-y-2">
        <label class="guest-field-label">{{ __('portal.phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input form-input-lg" placeholder="+255 …">
    </div>
    <div class="space-y-2">
        <label class="guest-field-label">{{ __('portal.room_number') }}</label>
        <select name="room_number" required class="form-input form-input-lg">
            <option value="">{{ __('portal.select_room') }}</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->number }}" @selected(old('room_number') == $room->number)>{{ $room->displayName() }}</option>
            @endforeach
        </select>
    </div>
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="space-y-2">
            <label class="guest-field-label">{{ __('portal.password') }}</label>
            <input type="password" name="password" required class="form-input form-input-lg">
        </div>
        <div class="space-y-2">
            <label class="guest-field-label">{{ __('portal.confirm_password') }}</label>
            <input type="password" name="password_confirmation" required class="form-input form-input-lg">
        </div>
    </div>
    <div class="space-y-2">
        <label class="guest-field-label">{{ __('portal.profile_picture_optional') }}</label>
        <input type="file" name="profile_picture" accept="image/jpeg,image/png,image/gif,image/webp" class="form-input form-input-lg file:mr-3 file:rounded-lg file:border-0 file:bg-navy-100 file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-navy-800">
        <p class="mt-1 text-xs text-slate-500">{{ __('portal.upload_hint') }}</p>
        @error('profile_picture')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn-primary btn-primary-lg w-full">{{ __('portal.submit_registration') }}</button>
</form>
<p class="mt-6 rounded-xl bg-slate-50 px-3 py-2 text-center text-xs text-slate-500 ring-1 ring-slate-100">
    {{ __('portal.room_max_members') }}
</p>
@endsection
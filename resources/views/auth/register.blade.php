@extends('layouts.guest')
@section('title', __('portal.register').' — Block L')
@section('content')
<h2 class="text-lg font-semibold text-navy-900">{{ __('portal.join_block_l') }}</h2>
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="mt-6 space-y-4">
    @csrf
    <div>
        <label class="text-sm font-medium">{{ __('portal.full_name') }}</label>
        <input type="text" name="name" value="{{ old('name') }}" required class="form-input mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.email') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="form-input mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.phone') }}</label>
        <input type="text" name="phone" value="{{ old('phone') }}" required class="form-input mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.room_number') }}</label>
        <select name="room_number" required class="form-input mt-1">
            <option value="">{{ __('portal.select_room') }}</option>
            @foreach ($rooms as $room)
                <option value="{{ $room->number }}" @selected(old('room_number') == $room->number)>{{ $room->displayName() }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.password') }}</label>
        <input type="password" name="password" required class="form-input mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.confirm_password') }}</label>
        <input type="password" name="password_confirmation" required class="form-input mt-1">
    </div>
    <div>
        <label class="text-sm font-medium">{{ __('portal.profile_picture_optional') }}</label>
        <input type="file" name="profile_picture" accept="image/*" class="form-input mt-1">
    </div>
    <button type="submit" class="btn-primary w-full">{{ __('portal.submit_registration') }}</button>
</form>
<p class="mt-4 text-center text-xs text-slate-500">{{ __('portal.room_max_members') }} {{ __('portal.approval_note') }}</p>
@endsection
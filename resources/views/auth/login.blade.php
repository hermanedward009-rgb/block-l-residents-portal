@extends('layouts.guest')
@section('title', __('portal.sign_in').' — Block L')
@section('content')
<h2 class="text-lg font-semibold text-navy-900">{{ __('portal.welcome_back') }}</h2>
<form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
    @csrf
    <div>
        <label class="text-sm font-medium text-slate-700">{{ __('portal.email') }}</label>
        <input type="email" name="email" value="{{ old('email') }}" required class="form-input mt-1" autocomplete="username">
    </div>
    <div>
        <label class="text-sm font-medium text-slate-700">{{ __('portal.password') }}</label>
        <input type="password" name="password" required class="form-input mt-1" autocomplete="current-password">
    </div>
    <label class="flex items-center gap-2 text-sm text-slate-600">
        <input type="checkbox" name="remember" class="rounded border-slate-300">
        {{ __('portal.remember_me') }}
    </label>
    <button type="submit" class="btn-primary w-full">{{ __('portal.sign_in') }}</button>
</form>
<p class="mt-6 text-center text-sm text-slate-600">{{ __('portal.new_resident') }} <a href="{{ route('register') }}" class="font-semibold text-navy-800 hover:underline">{{ __('portal.register') }}</a></p>
@endsection
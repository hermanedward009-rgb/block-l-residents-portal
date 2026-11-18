@extends('layouts.guest')
@section('title', __('portal.sign_in').' — Block L')
@section('content')
<header class="border-b border-slate-100 pb-6 text-center sm:text-left">
    <h2 class="text-2xl font-bold tracking-tight text-navy-900 lg:text-[1.65rem]">{{ __('portal.welcome_back') }}</h2>
    <p class="mt-2 text-sm leading-relaxed text-slate-500">
        {{ __('portal.sign_in') }} — {{ __('portal.residents_portal') }}
    </p>
</header>
<form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
    @csrf
    <div class="space-y-1.5">
        <label for="login-email" class="text-sm font-semibold text-slate-700">{{ __('portal.email') }}</label>
        <input
            id="login-email"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required
            class="form-input form-input-lg"
            autocomplete="username"
            placeholder="name@example.com"
        >
    </div>
    <div class="space-y-1.5">
        <label for="login-password" class="text-sm font-semibold text-slate-700">{{ __('portal.password') }}</label>
        <input
            id="login-password"
            type="password"
            name="password"
            required
            class="form-input form-input-lg"
            autocomplete="current-password"
        >
    </div>
    <div class="flex items-center justify-between gap-3 pt-1">
        <label class="flex cursor-pointer items-center gap-2.5 text-sm text-slate-600">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-navy-800 focus:ring-navy-600">
            {{ __('portal.remember_me') }}
        </label>
    </div>
    <button type="submit" class="btn-primary btn-primary-lg w-full">{{ __('portal.sign_in') }}</button>
</form>
<p class="mt-8 border-t border-slate-100 pt-6 text-center text-sm text-slate-600">
    {{ __('portal.new_resident') }}
    <a href="{{ route('register') }}" class="font-semibold text-navy-800 underline-offset-2 hover:underline">{{ __('portal.register') }}</a>
</p>
@endsection
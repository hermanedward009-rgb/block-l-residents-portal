@extends('layouts.guest')
@section('title', __('portal.sign_in').' — Block L')
@section('content')
<header class="text-center sm:text-left">
    <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-gradient-to-br from-navy-800 to-emerald-700 text-2xl shadow-lg shadow-navy-900/20 sm:mx-0" aria-hidden="true">
        🔐
    </div>
    <h2 class="text-2xl font-bold tracking-tight text-navy-900 sm:text-[1.75rem]">{{ __('portal.welcome_back') }}</h2>
    <div class="guest-heading-accent mx-auto sm:mx-0" aria-hidden="true"></div>
    <p class="mt-4 text-sm leading-relaxed text-slate-500">
        {{ __('portal.sign_in') }} to {{ __('portal.residents_portal') }}
    </p>
</header>
<form method="POST" action="{{ route('login') }}" class="mt-8 space-y-5">
    @csrf
    <div class="space-y-2">
        <label for="login-email" class="guest-field-label">
            <span class="guest-field-icon" aria-hidden="true">✉</span>
            {{ __('portal.email') }}
        </label>
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
    <div class="space-y-2">
        <label for="login-password" class="guest-field-label">
            <span class="guest-field-icon" aria-hidden="true">🔑</span>
            {{ __('portal.password') }}
        </label>
        <input
            id="login-password"
            type="password"
            name="password"
            required
            class="form-input form-input-lg"
            autocomplete="current-password"
            placeholder="••••••••"
        >
    </div>
    <div class="flex items-center justify-between gap-3 pt-1">
        <label class="flex cursor-pointer items-center gap-2.5 rounded-lg border border-transparent px-1 py-1 text-sm text-slate-600 transition hover:border-slate-200 hover:bg-slate-50">
            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
            {{ __('portal.remember_me') }}
        </label>
    </div>
    <button type="submit" class="btn-primary btn-primary-lg mt-2 w-full">{{ __('portal.sign_in') }}</button>
</form>
<p class="mt-8 border-t border-slate-100 pt-6 text-center text-sm text-slate-600">
    {{ __('portal.new_resident') }}
    <a href="{{ route('register') }}" class="guest-footer-link">
        {{ __('portal.register') }}
        <span aria-hidden="true">→</span>
    </a>
</p>
@endsection
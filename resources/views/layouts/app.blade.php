<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('portal.app_name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="portal-app min-h-screen bg-slate-100 text-slate-800 antialiased">
@php($user = auth()->user())
<div class="lg:flex">
    <aside class="portal-sidebar hidden lg:block">
        <div class="border-b border-white/10 p-6">
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-400">{{ __('portal.block_l') }}</div>
            <h1 class="mt-1 text-lg font-bold">{{ __('portal.residents_portal') }}</h1>
        </div>
        <nav class="space-y-1 p-4">
            @include('layouts.partials.nav-links')
        </nav>
        <div class="mt-auto border-t border-white/10 p-4 lg:absolute lg:bottom-0 lg:w-72">
            <div class="flex items-center gap-3 rounded-xl border border-white/10 bg-white/5 p-2 transition hover:border-emerald-400/30 hover:bg-white/10">
                @include('partials.avatar', ['user' => $user, 'size' => 'sm', 'class' => 'ring-2 ring-emerald-500/30 transition hover:ring-emerald-400/60'])
                <div class="min-w-0">
                    <p class="truncate text-sm font-semibold">{{ $user->name }}</p>
                    <p class="truncate text-xs text-slate-300">@if($user->room_number) Room {{ $user->room_number }} @else Administrator @endif</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mt-3">
                @csrf
                <button type="submit" class="w-full rounded-xl px-2 py-1.5 text-xs text-slate-300 transition hover:bg-white/10 hover:text-white">{{ __('portal.sign_out') }}</button>
            </form>
        </div>
    </aside>

    <div id="mobile-sidebar" class="fixed inset-0 z-50 hidden lg:hidden">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition" data-sidebar-toggle></div>
        <aside class="relative h-full w-72 bg-gradient-to-b from-navy-950 via-navy-900 to-navy-950 p-4 text-white shadow-2xl">
            <div class="mb-4 flex items-center justify-between">
                <span class="font-bold">BLOCK L</span>
                <button type="button" data-sidebar-toggle class="portal-icon-btn border-white/20 bg-white/10 text-white hover:bg-white/20">✕</button>
            </div>
            <nav class="space-y-1">@include('layouts.partials.nav-links')</nav>
            <form method="POST" action="{{ route('logout') }}" class="absolute bottom-4 left-4 right-4 border-t border-white/10 pt-4">
                @csrf
                <button type="submit" class="flex w-full items-center justify-center gap-2 rounded-xl bg-white/10 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-600/80">{{ __('portal.sign_out') }}</button>
            </form>
        </aside>
    </div>

    <div class="flex min-h-screen min-w-0 flex-1 flex-col">
        <header class="portal-topbar">
            <div class="flex items-center justify-between gap-4 px-4 py-3 lg:px-8">
                <button type="button" data-sidebar-toggle class="portal-icon-btn lg:hidden" aria-label="Menu">☰</button>
                <div class="flex-1">
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-700/80">{{ __('portal.app_name') }}</p>
                    <h2 class="text-lg font-bold text-navy-900">@yield('heading')</h2>
                </div>
                @include('layouts.partials.language-switcher')
                <button type="button" data-enable-push class="hidden rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-800 shadow-sm transition hover:border-emerald-400 hover:bg-emerald-100 hover:shadow-md sm:inline-flex">
                    {{ __('portal.enable_push') }}
                </button>
                <p data-push-enabled class="hidden text-xs font-semibold text-emerald-600">Push enabled</p>
                <a href="{{ route('notifications.index') }}" class="portal-icon-btn relative" aria-label="Notifications">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .53-.21 1.04-.59 1.41L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <span data-notification-count class="absolute -right-1 -top-1 hidden min-w-[1.25rem] rounded-full bg-emerald-500 px-1 text-center text-[10px] font-bold text-white shadow-md">0</span>
                </a>
            </div>
        </header>

        <main class="portal-main animate-fade-in">
            @if (session('status'))
                <div class="portal-callout-info mb-4">{{ session('status') }}</div>
            @endif
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 shadow-sm transition hover:border-red-300">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </main>

        <nav class="portal-bottom-nav">
            @include('layouts.partials.mobile-nav')
        </nav>
        <div class="h-[4.5rem] lg:hidden"></div>
    </div>
</div>
</body>
</html>
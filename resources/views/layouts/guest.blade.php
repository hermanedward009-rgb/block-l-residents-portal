<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('portal.app_name'))</title>
    @vite(['resources/css/app.css', 'resources/js/guest.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    @php
        $heroImage = asset('images/block-l-building.jpg?v=1');
        $iconImage = asset('images/block-l-icon.svg');
    @endphp
    <div class="lg:grid lg:min-h-screen lg:grid-cols-2">
        {{-- Desktop hero: gradient paints first, photo enhances when loaded --}}
        <aside
            class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-navy-950 via-navy-900 to-slate-900 lg:block"
            aria-hidden="true"
        >
            <img
                src="{{ $heroImage }}"
                alt=""
                width="1200"
                height="1600"
                class="guest-hero-photo absolute inset-0 h-full w-full object-cover"
                decoding="async"
                fetchpriority="low"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-navy-950/95 via-navy-900/75 to-navy-900/40"></div>

            <div class="relative z-10 flex min-h-screen flex-col justify-end p-10 xl:p-14">
                <p class="text-xs font-semibold uppercase tracking-[0.35em] text-emerald-400">{{ __('portal.block_l') }}</p>
                <h1 class="mt-3 max-w-md text-3xl font-bold leading-tight text-white xl:text-4xl">
                    {{ __('portal.residents_portal') }}
                </h1>
                <p class="mt-4 max-w-sm text-base leading-relaxed text-slate-200">
                    {{ __('portal.tagline') }}
                </p>
                <ul class="mt-8 grid max-w-md gap-3 text-sm text-slate-300">
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 text-emerald-400" aria-hidden="true">✓</span>
                        {{ __('portal.electricity') }}
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 text-emerald-400" aria-hidden="true">✓</span>
                        {{ __('portal.toilet_rota') }}
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-white/10 text-emerald-400" aria-hidden="true">✓</span>
                        {{ __('portal.announcements') }}
                    </li>
                </ul>
            </div>
        </aside>

        {{-- Form column --}}
        <main class="flex min-h-screen flex-col lg:min-h-0">
            <header class="flex items-center justify-between gap-4 px-5 pt-5 sm:px-8 lg:px-12 lg:pt-8">
                <p class="hidden text-sm font-semibold text-navy-900 lg:block">{{ __('portal.block_l') }}</p>
                <div class="flex items-center gap-3 lg:hidden">
                    <div class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-xl bg-navy-900 shadow-md ring-1 ring-slate-200">
                        <img src="{{ $iconImage }}" alt="Block L" class="h-8 w-8" width="32" height="32">
                    </div>
                    <div>
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-emerald-600">{{ __('portal.block_l') }}</p>
                        <p class="text-sm font-bold text-navy-900">{{ __('portal.residents_portal') }}</p>
                    </div>
                </div>
                @include('layouts.partials.language-switcher')
            </header>

            <div class="flex flex-1 flex-col items-center justify-center px-5 py-6 sm:px-8 lg:px-12 lg:py-10">
                <div class="w-full max-w-[22rem] sm:max-w-md">
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800" role="alert">
                            <ul class="space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg shadow-slate-200/60 sm:p-8 lg:rounded-3xl lg:p-9">
                        @yield('content')
                    </div>
                </div>
            </div>

            <p class="pb-6 text-center text-xs text-slate-400 lg:pb-8">
                © {{ date('Y') }} Block L · {{ __('portal.residents_portal') }}
            </p>
        </main>
    </div>
</body>
</html>
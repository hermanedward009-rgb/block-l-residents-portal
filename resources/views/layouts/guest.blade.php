<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('portal.app_name'))</title>
    @vite(['resources/css/app.css', 'resources/js/guest.js'])
</head>
<body class="guest-auth min-h-screen text-slate-900 antialiased">
    @php
        $heroImage = asset('images/block-l-building.jpg?v=1');
        $iconImage = asset('images/block-l-icon.svg');
    @endphp
    <div class="lg:grid lg:min-h-screen lg:grid-cols-[1.05fr_0.95fr] xl:grid-cols-2">
        {{-- Desktop hero --}}
        <aside
            class="relative hidden min-h-screen overflow-hidden bg-gradient-to-br from-navy-950 via-navy-900 to-slate-950 lg:block"
            aria-hidden="true"
        >
            <div class="guest-hero-glow absolute inset-0" aria-hidden="true"></div>
            <div class="guest-orb -left-24 top-1/4 h-72 w-72 bg-emerald-500/25" aria-hidden="true"></div>
            <div class="guest-orb right-0 top-0 h-96 w-96 bg-teal-400/15" aria-hidden="true"></div>

            <img
                src="{{ $heroImage }}"
                alt=""
                width="1200"
                height="1600"
                class="guest-hero-photo absolute inset-0 h-full w-full object-cover mix-blend-soft-light"
                decoding="async"
                fetchpriority="low"
            >
            <div class="absolute inset-0 bg-gradient-to-t from-navy-950 via-navy-950/85 to-navy-900/50"></div>

            <div class="relative z-10 flex min-h-screen flex-col justify-between p-10 xl:p-14">
                <div>
                    <span class="guest-hero-badge">
                        <span class="h-2 w-2 rounded-full bg-emerald-400 shadow-[0_0_12px_rgba(52,211,153,0.8)]" aria-hidden="true"></span>
                        {{ __('portal.block_l') }}
                    </span>
                </div>

                <div>
                    <h1 class="max-w-lg text-3xl font-bold leading-[1.15] tracking-tight text-white xl:text-4xl 2xl:text-[2.65rem]">
                        {{ __('portal.residents_portal') }}
                    </h1>
                    <p class="mt-4 max-w-md text-base leading-relaxed text-slate-300">
                        {{ __('portal.tagline') }}
                    </p>

                    <div class="mt-10 grid max-w-md gap-3">
                        <div class="guest-feature-card">
                            <span class="guest-feature-icon" aria-hidden="true">⚡</span>
                            <div>
                                <p class="font-semibold text-white">{{ __('portal.electricity') }}</p>
                                <p class="text-xs text-slate-400">Track contributions &amp; payments</p>
                            </div>
                        </div>
                        <div class="guest-feature-card">
                            <span class="guest-feature-icon" aria-hidden="true">🚽</span>
                            <div>
                                <p class="font-semibold text-white">{{ __('portal.toilet_rota') }}</p>
                                <p class="text-xs text-slate-400">Fair shared cleaning schedule</p>
                            </div>
                        </div>
                        <div class="guest-feature-card">
                            <span class="guest-feature-icon" aria-hidden="true">📢</span>
                            <div>
                                <p class="font-semibold text-white">{{ __('portal.announcements') }}</p>
                                <p class="text-xs text-slate-400">Stay in sync with Block L</p>
                            </div>
                        </div>
                    </div>
                </div>

                <p class="text-xs font-medium uppercase tracking-widest text-slate-500">
                    10 rooms · One community
                </p>
            </div>
        </aside>

        {{-- Form column --}}
        <main class="relative flex min-h-screen flex-col lg:min-h-0">
            <div class="guest-orb -right-20 top-20 h-56 w-56 bg-emerald-400/20 lg:hidden" aria-hidden="true"></div>

            <header class="relative z-10 flex items-center justify-between gap-4 px-5 pt-6 sm:px-8 lg:px-12 lg:pt-10">
                <div class="hidden items-center gap-3 lg:flex">
                    <div class="portal-brand-icon-box h-10 w-10 rounded-xl shadow-md ring-2 ring-white/80">
                        <img src="{{ $iconImage }}" alt="" class="portal-brand-icon rounded-xl" width="40" height="40">
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-emerald-700">{{ __('portal.block_l') }}</p>
                        <p class="text-sm font-bold text-navy-900">{{ __('portal.residents_portal') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 lg:hidden">
                    <div class="portal-brand-icon-box h-12 w-12 rounded-2xl shadow-lg shadow-navy-900/15 ring-2 ring-white/60">
                        <img src="{{ $iconImage }}" alt="Block L" class="portal-brand-icon rounded-2xl" width="48" height="48">
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-emerald-600">{{ __('portal.block_l') }}</p>
                        <p class="text-sm font-bold text-navy-900">{{ __('portal.residents_portal') }}</p>
                    </div>
                </div>
                @include('layouts.partials.language-switcher')
            </header>

            <div class="relative z-10 flex flex-1 flex-col items-center justify-center px-5 py-8 sm:px-8 lg:px-12 lg:py-12">
                <div class="w-full max-w-[24rem] sm:max-w-md">
                    @if ($errors->any())
                        <div class="mb-6 rounded-2xl border border-red-200/80 bg-red-50/90 px-4 py-3 text-sm text-red-800 shadow-sm backdrop-blur" role="alert">
                            <ul class="space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                        </div>
                    @endif
                    <div class="guest-card">
                        <div class="guest-card-shine" aria-hidden="true"></div>
                        @yield('content')
                    </div>
                </div>
            </div>

            <p class="relative z-10 pb-8 text-center text-xs text-slate-500 lg:pb-10">
                © {{ date('Y') }} Block L · {{ __('portal.residents_portal') }}
            </p>
        </main>
    </div>
</body>
</html>
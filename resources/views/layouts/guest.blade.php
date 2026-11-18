<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('portal.app_name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-navy-900 via-navy-800 to-slate-900">
    <div class="absolute right-4 top-4 z-10">
        @include('layouts.partials.language-switcher')
    </div>
    <div class="mx-auto flex min-h-screen max-w-5xl flex-col items-center justify-center gap-8 px-4 py-14 lg:flex-row lg:gap-12">
        <div class="w-full shrink-0 text-center lg:text-left">
            <div class="mx-auto h-14 w-14 overflow-hidden rounded-lg shadow-md ring-1 ring-white/20 lg:mx-0">
                <img src="{{ asset('images/block-l-building.jpg') }}?v={{ filemtime(public_path('images/block-l-building.jpg')) }}" alt="Block L" class="h-full w-full object-cover object-center" width="56" height="56" loading="eager">
            </div>
            <div class="mt-3 text-white lg:mt-4">
                <p class="text-xs font-semibold uppercase tracking-[0.3em] text-emerald-400">{{ __('portal.block_l') }}</p>
                <h1 class="mt-2 text-xl font-bold lg:text-2xl">{{ __('portal.residents_portal') }}</h1>
                <p class="mt-2 text-sm text-slate-300">{{ __('portal.tagline') }}</p>
            </div>
        </div>
        <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl shadow-black/25 lg:p-8">
            @if ($errors->any())
                <div class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                    <ul class="list-inside list-disc">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif
            @yield('content')
        </div>
    </div>
</body>
</html>
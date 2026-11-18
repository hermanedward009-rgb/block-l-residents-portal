@extends('layouts.guest')
@section('title', __('portal.pending_approval_title'))
@section('content')
<div class="text-center">
    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-amber-100 to-orange-100 text-3xl shadow-inner ring-1 ring-amber-200/80">⏳</div>
    <h2 class="mt-5 text-xl font-bold text-navy-900">{{ __('portal.pending_approval_title') }}</h2>
    <div class="guest-heading-accent mx-auto" aria-hidden="true"></div>
    <p class="mt-4 text-sm leading-relaxed text-slate-600">{{ __('portal.pending_approval_body') }}</p>
    <form method="POST" action="{{ route('logout') }}" class="mt-8">
        @csrf
        <button type="submit" class="btn-secondary w-full">{{ __('portal.sign_out') }}</button>
    </form>
</div>
@endsection
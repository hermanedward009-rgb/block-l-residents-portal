@extends('layouts.guest')
@section('title', __('portal.pending_approval_title'))
@section('content')
<div class="text-center">
    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-amber-100 text-2xl">⏳</div>
    <h2 class="mt-4 text-lg font-semibold text-navy-900">{{ __('portal.pending_approval_title') }}</h2>
    <p class="mt-3 text-sm text-slate-600">{{ __('portal.pending_approval_body') }}</p>
    <form method="POST" action="{{ route('logout') }}" class="mt-6">
        @csrf
        <button type="submit" class="btn-secondary w-full">{{ __('portal.sign_out') }}</button>
    </form>
</div>
@endsection
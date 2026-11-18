@extends('layouts.app')
@section('heading', 'Notifications')
@section('content')
<form method="POST" action="{{ route('notifications.read-all') }}" class="mb-4 text-right">@csrf<button class="btn-secondary text-xs">Mark all read</button></form>
<div class="space-y-3">
    @forelse ($notifications as $notification)
        <div class="flex items-start justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-4 {{ $notification->read_at ? 'opacity-75' : 'border-l-4 border-l-emerald-500' }}">
            <div>
                <p class="font-semibold text-navy-900">{{ $notification->title }}</p>
                <p class="mt-1 text-sm text-slate-600">{{ $notification->body }}</p>
                <p class="mt-2 text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</p>
            </div>
            @unless($notification->read_at)
                <form method="POST" action="{{ route('notifications.read', $notification) }}">@csrf @method('PATCH')<button class="text-xs font-semibold text-navy-800">Mark read</button></form>
            @endunless
        </div>
    @empty
        <p class="text-slate-500">You're all caught up.</p>
    @endforelse
</div>
{{ $notifications->links() }}
@endsection
@extends('layouts.app')
@section('heading', 'Announcements')
@section('content')
<div class="space-y-4">
    @forelse ($announcements as $item)
        <article class="rounded-2xl border border-slate-200 bg-white p-5">
            <h3 class="text-lg font-semibold text-navy-900">{{ $item->title }}</h3>
            <p class="mt-1 text-xs text-slate-500">Posted {{ $item->created_at->format('M j, Y g:i A') }} by {{ $item->author->name }} · {{ $item->audienceLabel() }}</p>
            <p class="mt-3 whitespace-pre-wrap text-sm text-slate-700">{{ $item->body }}</p>
        </article>
    @empty
        <p class="text-slate-500">No announcements published yet.</p>
    @endforelse
    {{ $announcements->links() }}
</div>
@endsection
@extends('layouts.app')
@section('heading', 'Moderate advice & suggestions')
@section('content')
<div class="space-y-3">
    @foreach($posts as $post)
        <article class="rounded-xl border border-slate-200 bg-white p-4 {{ $post->is_removed ? 'opacity-60' : '' }}">
            <p class="font-semibold">{{ $post->subject }} <span class="text-xs font-normal text-slate-500">by {{ $post->user->name }}</span></p>
            <p class="mt-2 text-sm text-slate-600">{{ Str::limit($post->body, 200) }}</p>
            <div class="mt-3">
                @if($post->is_removed)
                    <form method="POST" action="{{ route('admin.advice.restore', $post) }}">@csrf<button class="text-xs text-emerald-700">Restore</button></form>
                @else
                    <form method="POST" action="{{ route('admin.advice.remove', $post) }}">@csrf<button class="text-xs text-red-600">Remove</button></form>
                @endif
            </div>
        </article>
    @endforeach
</div>
{{ $posts->links() }}
@endsection
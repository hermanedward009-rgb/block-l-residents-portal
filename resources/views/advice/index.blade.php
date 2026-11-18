@extends('layouts.app')
@section('heading', 'Advice & Suggestions')
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="rounded-2xl border border-slate-200 bg-white p-5 lg:col-span-1">
        <h3 class="font-semibold">Share with Block L</h3>
        <form method="POST" action="{{ route('advice.store') }}" class="mt-4 space-y-3">
            @csrf
            <input name="subject" placeholder="Subject" class="form-input" required>
            <select name="category" class="form-input" required>
                @foreach($categories as $cat)<option value="{{ $cat }}">{{ $cat }}</option>@endforeach
            </select>
            <textarea name="body" rows="5" class="form-input" placeholder="Your message..." required></textarea>
            <button class="btn-primary w-full">Publish</button>
        </form>
    </div>
    <div class="space-y-4 lg:col-span-2">
        @foreach($posts as $post)
            <article class="rounded-2xl border border-slate-200 bg-white p-5">
                <div class="flex items-start gap-3">
                    <img src="{{ $post->user->avatarUrl() }}" class="h-10 w-10 rounded-full" alt="">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-semibold text-navy-900">{{ $post->subject }}</h4>
                            <span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs">{{ $post->category }}</span>
                        </div>
                        <p class="text-xs text-slate-500">{{ $post->user->name }} · Room {{ $post->user->room_number }} · {{ $post->created_at->format('M j, Y g:i A') }}</p>
                        <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $post->body }}</p>
                        <div class="mt-4 flex items-center gap-4 text-sm">
                            <form method="POST" action="{{ route('advice.react', $post) }}">@csrf
                                <button class="text-emerald-700 hover:underline">👍 {{ $post->reactions_count }} acknowledgements</button>
                            </form>
                            <span>{{ $post->comments_count }} comments</span>
                        </div>
                        @foreach($post->comments as $comment)
                            <div class="mt-3 rounded-xl bg-slate-50 p-3 text-sm">
                                <strong>{{ $comment->user->name }}</strong> <span class="text-xs text-slate-500">Room {{ $comment->user->room_number }}</span>
                                <p class="mt-1">{{ $comment->body }}</p>
                            </div>
                        @endforeach
                        <form method="POST" action="{{ route('advice.comment', $post) }}" class="mt-3 flex gap-2">@csrf
                            <input name="body" class="form-input flex-1" placeholder="Write a comment..." required>
                            <button class="btn-secondary">Post</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
        {{ $posts->links() }}
    </div>
</div>
@endsection
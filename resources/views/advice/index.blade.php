@extends('layouts.app')
@section('heading', 'Advice & Suggestions')
@section('content')
<div class="grid gap-6 lg:grid-cols-3">
    <div class="portal-card p-5 lg:col-span-1">
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
            <article class="portal-article">
                <div class="flex items-start gap-3">
                    @include('partials.avatar', ['user' => $post->user, 'size' => 'sm'])
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <h4 class="font-semibold text-navy-900">{{ $post->subject }}</h4>
                            <span class="portal-chip">{{ $post->category }}</span>
                        </div>
                        <p class="text-xs text-slate-500">{{ $post->user->name }} · Room {{ $post->user->room_number }} · {{ $post->created_at->format('M j, Y g:i A') }}</p>
                        <p class="mt-3 text-sm text-slate-700 whitespace-pre-wrap">{{ $post->body }}</p>
                        <div class="mt-4 flex items-center gap-4 text-sm">
                            <form method="POST" action="{{ route('advice.react', $post) }}">@csrf
                                <button class="btn-inline-success">👍 {{ $post->reactions_count }} acknowledgements</button>
                            </form>
                            <span>{{ $post->comments_count }} comments</span>
                        </div>
                        @foreach($post->comments as $comment)
                            <div class="portal-comment">
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
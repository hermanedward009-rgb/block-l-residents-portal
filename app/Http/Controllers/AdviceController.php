<?php

namespace App\Http\Controllers;

use App\Models\AdviceComment;
use App\Models\AdvicePost;
use App\Models\AdviceReaction;
use App\Services\PortalNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AdviceController extends Controller
{
    public function index()
    {
        $posts = AdvicePost::query()
            ->where('is_removed', false)
            ->with([
                'user',
                'comments' => fn ($q) => $q->latest()->limit(5)->with('user'),
            ])
            ->withCount(['comments', 'reactions'])
            ->latest()
            ->paginate(8);

        return view('advice.index', [
            'posts' => $posts,
            'categories' => ['Advice', 'Suggestion', 'Complaint', 'Security', 'Maintenance', 'Other'],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'category' => ['required', Rule::in(['Advice', 'Suggestion', 'Complaint', 'Security', 'Maintenance', 'Other'])],
            'body' => ['required', 'string', 'max:5000'],
        ]);

        $user = $request->user();
        $post = AdvicePost::create([
            ...$validated,
            'user_id' => $user->id,
        ]);

        PortalNotifier::notifyApprovedResidents(
            'advice',
            'New advice & suggestion',
            "{$user->name} published: {$post->subject}",
            ['post_id' => $post->id],
            $user,
        );

        PortalNotifier::log($user, 'advice', "Published advice: {$post->subject}");

        return back()->with('status', 'Your message has been published.');
    }

    public function comment(Request $request, AdvicePost $post)
    {
        abort_if($post->is_removed, 404);

        $validated = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
        ]);

        $user = $request->user();
        AdviceComment::create([
            'advice_post_id' => $post->id,
            'user_id' => $user->id,
            'body' => $validated['body'],
        ]);

        PortalNotifier::notifyApprovedResidents(
            'advice_comment',
            'New comment on discussion',
            "{$user->name} commented on \"{$post->subject}\".",
            ['post_id' => $post->id],
            $user,
        );

        return back()->with('status', 'Comment added.');
    }

    public function react(Request $request, AdvicePost $post)
    {
        abort_if($post->is_removed, 404);

        $user = $request->user();

        $existing = AdviceReaction::query()
            ->where('advice_post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            $existing->delete();

            return back();
        }

        AdviceReaction::create([
            'advice_post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        return back();
    }
}
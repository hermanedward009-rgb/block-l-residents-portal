<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdvicePost;
use Illuminate\Http\Request;

class AdminAdviceController extends Controller
{
    public function index()
    {
        return view('admin.advice.index', [
            'posts' => AdvicePost::query()->with('user')->latest()->paginate(20),
        ]);
    }

    public function remove(AdvicePost $post)
    {
        $post->update(['is_removed' => true]);

        return back()->with('status', 'Message removed from public view.');
    }

    public function restore(AdvicePost $post)
    {
        $post->update(['is_removed' => false]);

        return back()->with('status', 'Message restored.');
    }
}
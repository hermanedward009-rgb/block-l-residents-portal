<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminActivityController extends Controller
{
    public function clear(Request $request): RedirectResponse
    {
        ActivityLog::query()->delete();

        return back()->with('status', __('portal.activity_log_cleared'));
    }
}
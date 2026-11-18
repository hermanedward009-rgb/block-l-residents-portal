<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PendingApprovalController extends Controller
{
    public function __invoke(Request $request)
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        return view('auth.pending');
    }
}
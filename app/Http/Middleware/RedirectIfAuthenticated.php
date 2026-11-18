<?php

namespace App\Http\Middleware;

use App\Enums\AccountStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->status === AccountStatus::Pending) {
                return redirect()->route('pending-approval');
            }

            if ($user->isApproved() || $user->isAdmin()) {
                return redirect()->route($user->isAdmin() ? 'admin.dashboard' : 'dashboard');
            }
        }

        return $next($request);
    }
}
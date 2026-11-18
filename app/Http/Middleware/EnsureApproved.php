<?php

namespace App\Http\Middleware;

use App\Enums\AccountStatus;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApproved
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->status === AccountStatus::Pending) {
            return redirect()->route('pending-approval');
        }

        if ($user->status === AccountStatus::Rejected) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your registration was rejected. Please contact the Block L administrator.']);
        }

        if ($user->status === AccountStatus::Deactivated) {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Your account has been deactivated. Please contact the Block L administrator.']);
        }

        return $next($request);
    }
}
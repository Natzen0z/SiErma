<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuditorMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->isAuditor()) {
            return redirect()->route('risk.index')->with('error', 'Anda tidak memiliki akses ke halaman auditor.');
        }

        return $next($request);
    }
}

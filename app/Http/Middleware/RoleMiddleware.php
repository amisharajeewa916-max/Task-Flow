<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => null,
                    'message' => 'Unauthenticated.',
                    'status' => 'error'
                ], 401);
            }
            return redirect()->route('login');
        }

        if (!in_array($request->user()->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'data' => null,
                    'message' => 'Forbidden.',
                    'status' => 'error'
                ], 403);
            }
            abort(403, 'Forbidden.');
        }

        return $next($request);
    }
}

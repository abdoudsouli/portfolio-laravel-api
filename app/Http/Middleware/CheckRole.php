<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,$roles): Response
    {

         $user = $request->user();

          if (empty($roles)) {
            return response()->json(['message' => 'No role specified in middleware.'], 500);
        }
        // If not authenticated or role doesn't match
        if (! $user || $user->role !== $roles) {
            return response()->json([
                'message' => 'Access denied. Only ' . $roles . 's can access this resources.'
            ], 403);
        }
        return $next($request);
    }
}

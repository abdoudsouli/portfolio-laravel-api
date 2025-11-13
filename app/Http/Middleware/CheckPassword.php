<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if($request->CheckPassword != env('CheckPassword','aSzwTYjKu1wWTzvEQTpPBhomG1ORSssgq1yc7')){
            return response()->json([
                'success'=>false,
                'message'=>"don't have permission to access this resource"
            ],422);
        }
        return $next($request);
    }
}

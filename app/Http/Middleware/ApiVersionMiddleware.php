<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiVersionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $version = 'v1.0'): Response
    {
        $request->headers->set('Accept', 'application/json');
        $request->headers->set('X-API-Version', $version);
        
        return $next($request);
    }
}
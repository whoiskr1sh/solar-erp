<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockJsonResponses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Check if the response is JSON and redirect to dashboard
        if ($response->headers->get('Content-Type') === 'application/json' && 
            !$request->is('api/*') && 
            !$request->is('ajax/*')) {
            
            return redirect()->route('dashboard')->with('error', 'This page is not accessible. Redirected to dashboard.');
        }
        
        return $response;
    }
}
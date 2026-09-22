<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class VerifyClient
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $headerName = 'X-FRONTIIR-CLIENT';
        $headerValue = config('setting.api.client-header');
        Log::debug("headerValue >>> " . $headerValue);

        $clientHeader = $request->header($headerName);
        Log::debug("Client HEader >>> " . $clientHeader);
        if ($clientHeader !== $headerValue) {
            return response()->json(['error' => 'UNAUTHORIZED'], Response::HTTP_UNAUTHORIZED);
        }
        return $next($request);
    }
}

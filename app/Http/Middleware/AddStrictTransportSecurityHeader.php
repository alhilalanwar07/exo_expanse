<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddStrictTransportSecurityHeader
{
    private const HSTS_VALUE = 'max-age=31536000; includeSubDomains';

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! app()->environment('production') || ! $request->isSecure()) {
            return $response;
        }

        $response->headers->set('Strict-Transport-Security', self::HSTS_VALUE);

        return $response;
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttpsInProduction
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('production') || $request->isSecure()) {
            return $next($request);
        }

        $secureUrl = 'https://'.$request->getHttpHost().$request->getRequestUri();

        return redirect()->to($secureUrl, 301);
    }
}

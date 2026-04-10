<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Allows the response to be embedded in an <iframe>.
 *
 * By default Laravel (via Symfony) sends `X-Frame-Options: SAMEORIGIN`.
 * This middleware removes that header and sets a permissive
 * `Content-Security-Policy: frame-ancestors *` so that the page can be
 * loaded inside a WebView / iframe from any origin (e.g. the mobile app's
 * in-app browser running on a different port during development).
 *
 * ⚠️  Only apply this to routes that are intentionally public demo pages.
 *     Never register it globally.
 */
class AllowIframeEmbedding
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->remove('X-Frame-Options');
        $response->headers->set('Content-Security-Policy', 'frame-ancestors *');

        return $response;
    }
}

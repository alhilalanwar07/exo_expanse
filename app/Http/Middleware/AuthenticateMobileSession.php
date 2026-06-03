<?php

namespace App\Http\Middleware;

use App\Services\MobileAccessService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateMobileSession
{
    public function __construct(
        private readonly MobileAccessService $mobileAccessService,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (! $bearerToken) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $session = $this->mobileAccessService->findActiveSessionByAccessToken($bearerToken);

        if (! $session || ! $session->user) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $this->mobileAccessService->touchSession($session, $request->ip(), $request->userAgent());

        $request->attributes->set('mobileSession', $session);
        $request->attributes->set('mobileUser', $session->user);

        return $next($request);
    }
}

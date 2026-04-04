<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MobileDeviceSession;
use App\Models\User;
use App\Services\MobileAccessService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class MobileAccessController extends Controller
{
    public function exchange(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $validated = $request->validate([
            'access_code' => 'required|string|max:64',
            'device_alias' => 'nullable|string|max:120',
            'platform' => 'nullable|in:ios,android,web',
        ]);

        try {
            $payload = $mobileAccessService->exchangeAccessCode(
                $validated['access_code'],
                $validated['device_alias'] ?? null,
                $validated['platform'] ?? null,
                $request->ip(),
                $request->userAgent()
            );

            return response()->json($payload);
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function refresh(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $validated = $request->validate([
            'refresh_token' => 'required|string|max:255',
        ]);

        try {
            $payload = $mobileAccessService->refreshSession(
                $validated['refresh_token'],
                $request->ip(),
                $request->userAgent()
            );

            return response()->json($payload);
        } catch (InvalidArgumentException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }
    }

    public function issue(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $validated = $request->validate([
            'device_alias' => 'nullable|string|max:120',
            'platform' => 'nullable|in:ios,android,web',
        ]);

        $user = $request->user();

        if (! $user instanceof User) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $payload = $mobileAccessService->issueAccessCode(
            $user,
            $validated['device_alias'] ?? null,
            $validated['platform'] ?? null
        );

        return response()->json($payload);
    }

    public function revoke(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $session = $request->attributes->get('mobileSession');

        if (! $session instanceof MobileDeviceSession) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        $mobileAccessService->revokeSession($session);

        return response()->json([
            'success' => true,
        ]);
    }

    public function devices(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $user = $request->attributes->get('mobileUser');
        $currentSession = $request->attributes->get('mobileSession');

        if (! $user instanceof User || ! $currentSession instanceof MobileDeviceSession) {
            return response()->json([
                'message' => 'Unauthorized.',
            ], 401);
        }

        return response()->json([
            'data' => $mobileAccessService->listDevices($user, $currentSession->id),
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MobileAccessService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class MobileAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        return response()->json([
            'message' => 'Registrasi berhasil. Link aktivasi akun sudah dikirim ke email Anda.',
            'data' => [
                'name' => $user->name,
                'email' => $user->email,
                'requires_email_verification' => true,
            ],
        ], 201);
    }

    public function login(Request $request, MobileAccessService $mobileAccessService): JsonResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
            'device_alias' => 'nullable|string|max:120',
            'platform' => 'nullable|in:ios,android,web',
        ]);

        $user = User::query()->where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password)) {
            return response()->json([
                'message' => 'Email atau password yang Anda masukkan salah.',
            ], 422);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Akun belum aktif. Silakan cek email Anda untuk aktivasi.',
                'requires_email_verification' => true,
            ], 403);
        }

        $payload = $mobileAccessService->createSessionForUser(
            $user,
            $validated['device_alias'] ?? null,
            $validated['platform'] ?? null,
            $request->ip(),
            $request->userAgent()
        );

        return response()->json($payload);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['nullable', 'string', 'email', 'required_without:login'],
            'login' => ['nullable', 'string', 'required_without:email'],
            'password' => ['required', 'string'],
            'device_name' => ['nullable', 'string', 'max:255'],
        ]);

        $authApiUrl = (string) config('services.external_auth.login_url', 'http://10.0.1.47/api/login');

        try {
            $response = Http::timeout(12)->acceptJson()->post($authApiUrl, [
                'email' => $validated['email'] ?? null,
                'login' => $validated['login'] ?? null,
                'password' => $validated['password'],
                'device_name' => $validated['device_name'] ?? 'web',
            ]);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'message' => 'Authentication failed',
                'status' => $response->status(),
                'details' => $response->json(),
            ], $response->status());
        } catch (\Throwable $exception) {
            return response()->json([
                'message' => 'Authentication service is unavailable',
                'error' => $exception->getMessage(),
            ], 503);
        }
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
                $payload = $response->json();
                $token = (string) ($payload['token'] ?? $payload['access_token'] ?? '');

                if ($token === '') {
                    return response()->json([
                        'message' => 'Authentication service returned invalid payload',
                    ], 502);
                }

                $rawUser = $payload['user'] ?? $payload['data']['user'] ?? [];
                $user = $this->normalizeSessionUser(is_array($rawUser) ? $rawUser : []);

                $request->session()->regenerate();
                $request->session()->put('library.crm_token', $token);
                $request->session()->put('library.user', $user);
                $request->session()->put('library.authenticated_at', now()->toIso8601String());

                Log::info('Library CRM login successful', [
                    'ip' => $request->ip(),
                    'login' => $validated['login'] ?? null,
                    'email' => $validated['email'] ?? null,
                    'role' => $user['role'],
                ]);

                return response()->json([
                    'success' => true,
                    'user' => $user,
                ]);
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

    public function me(Request $request): JsonResponse
    {
        $user = $request->session()->get('library.user');

        if (! is_array($user)) {
            return response()->json([
                'authenticated' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        return response()->json([
            'authenticated' => true,
            'user' => $user,
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $crmToken = (string) $request->session()->get('library.crm_token', '');
        $logoutApiUrl = (string) config('services.external_auth.logout_url', '');

        if ($crmToken !== '' && $logoutApiUrl !== '') {
            try {
                Http::timeout(8)
                    ->acceptJson()
                    ->withToken($crmToken)
                    ->post($logoutApiUrl);
            } catch (\Throwable $exception) {
                Log::warning('CRM logout request failed', [
                    'ip' => $request->ip(),
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
        ]);
    }

    /**
     * @param array<string, mixed> $user
     * @return array<string, string>
     */
    private function normalizeSessionUser(array $user): array
    {
        $role = mb_strtolower(trim((string) ($user['role'] ?? 'reader')));
        $allowedRoles = ['reader', 'librarian', 'admin'];

        if (! in_array($role, $allowedRoles, true)) {
            $role = 'reader';
        }

        return [
            'id' => (string) ($user['id'] ?? ''),
            'name' => (string) ($user['name'] ?? ''),
            'email' => (string) ($user['email'] ?? ''),
            'login' => (string) ($user['login'] ?? ''),
            'ad_login' => (string) ($user['ad_login'] ?? ''),
            'role' => $role,
        ];
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:admin,manager,user',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error'
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'Registration successful.',
            'status' => 'success'
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors(),
                'message' => 'Validation error.',
                'status' => 'error'
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'data' => null,
                'message' => 'Invalid login credentials.',
                'status' => 'error'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
            ],
            'message' => 'Login successful.',
            'status' => 'success'
        ], 200);
    }

    public function logout(Request $request)
    {
        // Revoke the current token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'data' => null,
            'message' => 'Token revoked successfully.',
            'status' => 'success'
        ], 200);
    }

    public function activeSessions(Request $request)
    {
        $user = $request->user();

        // 1. Get active database browser sessions
        $dbSessions = \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($request) {
                $agent = tap(new \Laravel\Jetstream\Agent(), fn ($a) => $a->setUserAgent($session->user_agent));
                return [
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'browser' => $agent->browser(),
                    'platform' => $agent->platform(),
                    'is_current_device' => $request->hasSession() && $session->id === $request->session()->getId(),
                    'last_active' => \Illuminate\Support\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                ];
            });

        // 2. Get active personal access tokens (Sanctum)
        $apiTokens = $user->tokens->map(function ($token) {
            return [
                'id' => $token->id,
                'name' => $token->name,
                'abilities' => $token->abilities,
                'last_used_at' => $token->last_used_at ? $token->last_used_at->diffForHumans() : null,
                'created_at' => $token->created_at->diffForHumans(),
            ];
        });

        return response()->json([
            'data' => [
                'browser_sessions' => $dbSessions,
                'api_tokens' => $apiTokens,
            ],
            'message' => 'Active sessions retrieved successfully.',
            'status' => 'success'
        ], 200);
    }
}

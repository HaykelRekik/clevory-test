<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Auth\LoginRequest;
use App\Http\Requests\API\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['email', 'password']);

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid credentials.'], 401);
            }
        } catch (JWTException $exception) {
            return response()->json(['error' => 'could not create token'], 500);
        }

        return response()
            ->json(data: [
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL().' minutes',
            ]);

    }

    public function logout(): JsonResponse
    {
        try {
            JWtauth::invalidate(JWTAuth::getToken());
        } catch (JWTException $exception) {
            return response()->json(['error' => 'could not logout'], 500);
        }

        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh(): JsonResponse
    {
        try {
            $token = JWTAuth::refresh();
        } catch (JWTException $exception) {
            return response()->json(['error' => 'could not refresh token'], 500);
        }

        return response()
            ->json(data: [
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL().' minutes',
            ]);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::query()
            ->create($request->validated());

        try {
            $token = JWTAuth::fromUser($user);
        } catch (JWTException $exception) {
            return response()->json(['error' => 'could not create token'], 500);
        }

        return response()
            ->json(data: [
                'token' => $token,
                'expires_in' => JWTAuth::factory()->getTTL().' minutes',
            ]);
    }
}

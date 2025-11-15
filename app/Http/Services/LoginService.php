<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{

    public function __construct(protected TokenService $tokenService, protected LoginAttemptService $loginAttemptService) {}

    public function login(array $data)
    {

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            if ($user && $user->hasRole('Citizen')) {
                $this->loginAttemptService->recordFailedAttempt($user);
            }
            return ApiResponse::sendError('Invalid email or password', 401);
        }
        $this->loginAttemptService->resetAttempts($user);

        $token = $this->tokenService->createToken($user);

        // حفظ device_token إذا موجود
        if (!empty($data['device_token'])) {
            DeviceToken::updateOrCreate(
                ['user_id' => $user->id],
                ['device_token' => $data['device_token']]
            );
        }

        $UserData = [
            'user'  => new UserResource($user),
            'token' => $token
        ];
        return ApiResponse::sendResponse(200, 'Login successful', $UserData);
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
        return ApiResponse::sendResponse(200, 'Logout successful');
    }
}

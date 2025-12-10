<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use App\Http\Services\UserActivityService;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginService
{

    public function __construct(protected TokenService $tokenService, protected LoginAttemptService $loginAttemptService, protected UserActivityService $activity) {}

    public function login(array $data)
    {

        $user = User::where('email', $data['email'])->first();
        if ($this->loginAttemptService->isLocked($user)) {
            $minutes = $this->loginAttemptService->lockedTimeRemaining($user);
            return ApiResponse::sendError("حسابك مقفل مؤقتًا. حاول مرة أخرى بعد $minutes دقيقة.", 423);
        }

        if (!$user || !Hash::check($data['password'], $user->password)) {
            if ($user && $user->hasRole('المواطن')) {
                $this->loginAttemptService->recordFailedAttempt($user);
            }
            return ApiResponse::sendError('البريد الإلكتروني أو كلمة المرور غير صحيحة', 401);
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
        $this->activity->add_activity($user->id,  'تم تسجيل الدخول');
        return ApiResponse::sendResponse(200, 'تم تسجيل الدخول بنجاح', $UserData);
    }

    public function logout($user)
    {
        $user->currentAccessToken()->delete();
        DeviceToken::where('user_id', $user->id)->delete();
        $this->activity->add_activity($user->id,  'تم تسجيل الخروج');

        return ApiResponse::sendResponse(200, 'تم تسجيل الخروج بنجاح');
    }
}

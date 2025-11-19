<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;

class CheckSessionService
{
    /**
     * التأكد من حالة جلسة المستخدم
     */
    public function check()
    {
        $user = Auth::user();

        if (!$user) {
            return ApiResponse::sendError('انتهت الجلسة أو أن رمز الدخول غير صالح.', 401);
        }

        return ApiResponse::sendResponse(200, 'الجلسة فعّالة.', new UserResource($user));
    }
}

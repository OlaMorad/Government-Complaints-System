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
            return ApiResponse::sendError('Session expired or token invalid.', 401);
        }

        return ApiResponse::sendResponse(200, 'Session is active.', new UserResource($user));
    }
}

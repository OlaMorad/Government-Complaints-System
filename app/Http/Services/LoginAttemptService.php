<?php

namespace App\http\Services;

use App\Models\User;
use App\Models\FailedLogin;


class LoginAttemptService
{
    const MAX_ATTEMPTS = 3;

    public function recordFailedAttempt(User $user): void
    {
        $failedLogin = FailedLogin::firstOrCreate(
            ['user_id' => $user->id],
            ['attempts' => 0, 'last_attempt_at' => now()]
        );

        $failedLogin->increment('attempts');
        $failedLogin->last_attempt_at = now();
        $failedLogin->save();

        if ($failedLogin->attempts >= self::MAX_ATTEMPTS) {
            app(\App\http\Services\FirebaseNotificationService::class)
                ->sendToUser(
                    $user->id,
                    'تنبيه!',
                    "تمت محاولة تسجيل الدخول الى حسابك يرجى تغيير كلمة المرور ",

                );
            $failedLogin->attempts = 0;
            $failedLogin->save();
        }
    }

    public function resetAttempts(User $user): void
    {
        FailedLogin::where('user_id', $user->id)->delete();
    }
}

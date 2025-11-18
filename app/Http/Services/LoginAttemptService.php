<?php

namespace App\Http\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Models\FailedLogin;
use App\Jobs\SendFirebaseNotification;

class LoginAttemptService
{
    const MAX_ATTEMPTS = 3;
    const LOCKOUT_MINUTES = 15; // مدة القفل المؤقت

    public function recordFailedAttempt(User $user): void
    {
        $failedLogin = FailedLogin::firstOrCreate(
            ['user_id' => $user->id],
            ['attempts' => 0, 'last_attempt_at' => now(), 'locked_until' => null]
        );

        if ($failedLogin->locked_until && Carbon::now()->lessThan($failedLogin->locked_until)) {
            return; // الحساب لا يزال مقفلًا، لا نزيد المحاولات
        }

        $failedLogin->increment('attempts');
        $failedLogin->last_attempt_at = now();

        if ($failedLogin->attempts >= self::MAX_ATTEMPTS) {
            // قفل الحساب مؤقتًا
            $failedLogin->locked_until = Carbon::now()->addMinutes(self::LOCKOUT_MINUTES);
            $failedLogin->attempts = 0;
SendFirebaseNotification::dispatch(
    $user->id,
    'تنبيه! حسابك مؤقتًا مقفل',
    "تمت محاولة تسجيل الدخول عدة مرات بشكل خاطئ. حسابك مقفل لمدة " . self::LOCKOUT_MINUTES . " دقيقة."
);
        }

        $failedLogin->save();
    }

    public function resetAttempts(User $user): void
    {
        FailedLogin::where('user_id', $user->id)->delete();
    }

    // تحقق إذا كان الحساب مقفل قبل السماح بتسجيل الدخول
    public function isLocked(User $user): bool
    {
        $failedLogin = FailedLogin::where('user_id', $user->id)->first();

        if ($failedLogin && $failedLogin->locked_until && Carbon::now()->lessThan($failedLogin->locked_until)) {
            return true;
        }

        return false;
    }

    public function lockedTimeRemaining(User $user): ?int
    {
        $failedLogin = FailedLogin::where('user_id', $user->id)->first();

        if ($failedLogin && $failedLogin->locked_until) {
            $diff = Carbon::now()->diffInMinutes($failedLogin->locked_until, false);
            return $diff > 0 ? $diff : null;
        }

        return null;
    }
}

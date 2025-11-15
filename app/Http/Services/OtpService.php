<?php
namespace App\Http\Services;

use App\Models\Otp;

class OtpService
{
    // توليد وحفظ OTP مع البيانات المؤقتة
    public function createOtp($name, $email, $password,$phone)
    {
        $otpCode = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);

        $otp = Otp::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => bcrypt($password),
                'otp_code' => $otpCode,
                'expires_at' => $expiresAt,
                'phone'=>$phone
            ]
        );

        return $otp;
    }

    // التحقق من OTP
    public function verifyOtp($email, $otpCode)
    {
        return Otp::where('email', $email)
                  ->where('otp_code', $otpCode)
                  ->where('expires_at', '>', now())
                  ->first();
    }

    // حذف OTP بعد التحقق
    public function deleteOtp($otp)
    {
        $otp->delete();
    }
}

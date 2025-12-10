<?php

namespace App\Http\Services;

use App\Http\Services\UserActivityService;
use App\Models\DeviceToken;
use App\Models\User;

class RegisterService
{

    public function __construct(protected OtpService $otpService, protected MailService $mailService, protected TokenService $tokenService, protected UserActivityService $activity) {}

    public function register($request)
    {
        $otp = $this->otpService->createOtp($request->name, $request->email, $request->password, $request->phone);
        $this->mailService->sendOtp($request->email, $otp->otp_code);

        return response()->json(['message' => 'تم إرسال رمز التحقق إلى بريدك الإلكتروني.']);
    }

    public function verifyAndCreateUser($email, $otpCode, $deviceToken = null)
    {
        $otp = $this->otpService->verifyOtp($email, $otpCode);
        if (!$otp) {
            return response()->json(['message' =>   'رمز التحقق غير صالح أو انتهت صلاحيته.'], 422);
        }

        $user = User::create([
            'name' => $otp->name,
            'email' => $otp->email,
            'password' => $otp->password,
            'phone' => $otp->phone,
        ]);
        $user->assignRole('المواطن');

        $this->otpService->deleteOtp($otp);
        $token = $this->tokenService->createToken($user);

        // حفظ device_token إذا موجود
        if (!empty($deviceToken)) {
            DeviceToken::updateOrCreate(
                ['user_id' => $user->id],
                ['device_token' => $deviceToken]
            );
        }

        $this->activity->add_activity($user->id,  'تم انشاء الحساب ');

        return response()->json(['message' => 'تم إنشاء الحساب بنجاح.', 'user' => $user, 'token' => $token]);
    }
}

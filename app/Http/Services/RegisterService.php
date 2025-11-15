<?php

namespace App\Http\Services;

use App\Models\User;

class RegisterService
{

    public function __construct(protected OtpService $otpService, protected MailService $mailService, protected TokenService $tokenService) {}

    public function register($request)
    {
        $otp = $this->otpService->createOtp($request->name, $request->email, $request->password, $request->phone);
        $this->mailService->sendOtp($request->email, $otp->otp_code);

        return response()->json(['message' => 'تم إرسال رمز التحقق إلى بريدك']);
    }

    public function verifyAndCreateUser($email, $otpCode)
    {
        $otp = $this->otpService->verifyOtp($email, $otpCode);
        if (!$otp) {
            return response()->json(['message' => 'رمز التحقق غير صالح أو منتهي'], 422);
        }

        $user = User::create([
            'name' => $otp->name,
            'email' => $otp->email,
            'password' => $otp->password,
            'phone' => $otp->phone,
        ]);
        $user->assignRole('Citizen');

        $this->otpService->deleteOtp($otp);
        $token = $this->tokenService->createToken($user);

        return response()->json(['message' => 'تم إنشاء الحساب بنجاح', 'user' => $user, 'token' => $token]);
    }
}

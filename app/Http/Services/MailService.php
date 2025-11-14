<?php
namespace app\Http\Services;

use App\Mail\OtpMail;
use App\Jobs\SendOtpEmailJob;
use Illuminate\Support\Facades\Mail;

class MailService
{
    public function sendOtp($email, $otpCode)
    {
        SendOtpEmailJob::dispatch($email, $otpCode);
    }
}


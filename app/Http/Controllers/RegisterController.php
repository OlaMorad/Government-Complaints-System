<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Http\Services\RegisterService;

class RegisterController extends Controller
{
    public function __construct(protected RegisterService $registerService){

    }
       public function register(RegisterRequest $request)
    {
      return  $this->registerService->register($request);

    }

    public function verifyOtp(VerifyOtpRequest $request)
    {
   return  $this->registerService->verifyAndCreateUser($request->email,$request->otp_code);

    }
}

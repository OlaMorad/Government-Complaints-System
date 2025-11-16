<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Services\CheckSessionService;
use App\Http\Services\LoginService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct(
        protected LoginService $loginService,
        protected CheckSessionService $checkSessionService
    ) {}

    public function login(LoginRequest $request)
    {
        return $this->loginService->login($request->validated());
    }

    public function logout()
    {
        $user = Auth::user();
        return $this->loginService->logout($user);
    }

    public function checkSession()
    {
        return $this->checkSessionService->check();
    }
}

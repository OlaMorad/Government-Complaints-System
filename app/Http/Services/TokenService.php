<?php

namespace App\Http\Services;

class TokenService
{
    public function createToken($user)
    {
        return $user->createToken('auth_token')->plainTextToken;
    }
}

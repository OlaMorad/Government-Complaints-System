<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable=[
        'name',
        'email',
        'password',
        'otp_code',
        'expires_at'
    ];
}

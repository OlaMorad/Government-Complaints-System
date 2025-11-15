<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FailedLogin extends Model
{
    protected $fillable = ['user_id', 'attempts', 'last_attempt_at'];
}

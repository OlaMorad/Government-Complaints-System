<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Model
{
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function governmentEntities()
    {
        return $this->belongsToMany(GovernmentEntity::class, 'employee_government_entities')
            ->withTimestamps();
    }
}

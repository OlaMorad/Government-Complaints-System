<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentEntity extends Model
{
    protected $fillable=[
        'name'
    ];

    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'government_entity_id');
    }
    
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_government_entities')
            ->withTimestamps();
    }
}

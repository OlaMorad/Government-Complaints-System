<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $fillable = [
        'user_id',
        'complaint_type_id',
        'government_entity_id',
        'location_description',
        'problem_description',
        'reference_number',
        'status'
    ];

    // protected $casts = [
    //     'attachments' => 'array',
    // ];

    public function attachments()
    {
        return $this->belongsToMany(
            ComplaintAttachment::class,
            'complaint_complaint_attachment',
            'complaint_id',
            'complaint_attachment_id'
        );
    }

    public function governmentEntity()
    {
        return $this->belongsTo(GovernmentEntity::class, 'government_entity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(ComplaintType::class, 'complaint_type_id');
    }
    public function history()
    {
        return $this->hasMany(ComplaintHistory::class, 'complaint_id');
    }
}

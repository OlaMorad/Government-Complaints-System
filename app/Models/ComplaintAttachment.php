<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    protected $fillable=[
        'file_path',
    ];
    public function complaints()
{
    return $this->belongsToMany(Complaint::class, 'complaint_attachment_complaint');
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplaintAttachment extends Model
{
    protected $hidden = ['pivot'];
    protected $fillable = [
        'file_path',
    ];
    public function complaints()
    {
        return $this->belongsToMany(Complaint::class, 'complaint_attachment_complaint');
    }

    public function getFilePathAttribute($value)
    {
        return asset('Storage/' . $value);
    }
}

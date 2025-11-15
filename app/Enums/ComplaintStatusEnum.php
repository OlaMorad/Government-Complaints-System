<?php
namespace App\Enums;

enum ComplaintStatusEnum: string
{
    case PENDING   = 'PENDING';
    case APPROVED  = 'APPROVED';
    case REJECTED  = 'REJECTED';
}

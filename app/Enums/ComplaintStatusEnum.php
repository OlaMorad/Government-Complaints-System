<?php
namespace App\Enums;

enum ComplaintStatusEnum: string
{
    case PENDING   = 'انتظار';
    case COMPLETED  = 'منجزة';
    case REJECTED  = 'مرفوضة';
    case IN_PROGRESS = 'قيد المعالجة';
}

<?php

namespace App\Http\Services;

use App\Enums\ComplaintStatusEnum;
use App\Helpers\ApiResponse;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Support\Facades\Auth;

class ComplaintStatusService
{
    /**
     * تبديل الحالة بين انتظار ↔ قيد المعالجة
     */
    public function toggleStatus(int $complaintId)
    {
        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return ApiResponse::sendError('الشكوى غير موجودة.', 404);
        }
        $newStatus =
            $complaint->status === ComplaintStatusEnum::PENDING->value
            ? ComplaintStatusEnum::IN_PROGRESS->value
            : ComplaintStatusEnum::PENDING->value;

        $complaint->update([
            'status'     => $newStatus,
        ]);

        return ApiResponse::sendResponse(200, 'تم تغيير حالة الشكوى بنجاح.', $newStatus);
    }

    public function updateStatus(int $complaintId, string $newStatus)
    {
        $complaint = Complaint::find($complaintId);

        if (!$complaint) {
            return ApiResponse::sendError('الشكوى غير موجودة.', 404);
        }
        
        // التحقق من أن الحالة الحالية هي "قيد المعالجة"
        if ($complaint->status !== ComplaintStatusEnum::IN_PROGRESS->value) {
            return ApiResponse::sendError('لا يمكنك تغيير حالة هذه الشكوى إذا لم تكن قيد المعالجة.', 403);
        }

        $complaint->status = $newStatus;
        $complaint->save();

        return ApiResponse::sendResponse(200, 'تم تغيير حالة الشكوى بنجاح.', $newStatus);
    }
}

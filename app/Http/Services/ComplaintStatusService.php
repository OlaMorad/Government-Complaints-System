<?php

namespace App\Http\Services;

use App\Enums\ComplaintStatusEnum;
use App\Helpers\ApiResponse;
use App\Http\Resources\ComplaintHistoryResource;
use App\Models\Complaint;
use App\Models\ComplaintHistory;
use Illuminate\Support\Facades\Auth;

class ComplaintStatusService
{
    public function __construct(protected FirebaseNotificationService $firebase) {}
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

    function addAttachmentHistory(int $complaintId, int $userId)
    {
        $complaint = Complaint::findOrFail($complaintId);

        // جلب آخر حالة للشكوى

        // إضافة سجل جديد في الهيستوري
        ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'old_status'    => $complaint->status,  // نفس الحالة القديمة
            'new_status' => $complaint->status,
            'changed_by' => $userId,
            'notes'        => "تم رفع مرفقات من قبل المواطن صاحب الـ ID {$userId}",
        ]);

        return true;
    }
    public function addNote(int $complaintId, string $note)
    {
        $user = Auth::user();

        $complaint = Complaint::with('history')->find($complaintId);

        if (!$complaint) {
            return ApiResponse::sendError('الشكوى غير موجودة.', 404);
        }

        // يجب أن تكون الشكوى قيد المعالجة
        if ($complaint->status !== ComplaintStatusEnum::IN_PROGRESS->value) {
            return ApiResponse::sendError('لا يمكن إضافة ملاحظة إلا إذا كانت الشكوى قيد المعالجة.', 403);
        }

        $lastHistory = $complaint->history()->latest()->first();

        if (!$lastHistory) {
            return ApiResponse::sendError('لا يوجد سجل تغييرات مرتبط بهذه الشكوى.', 400);
        }

        // يجب أن يكون نفس الموظف الذي غير الحالة هو نفسه الذي يضيف الملاحظة
        if ($lastHistory->changed_by !== $user->id) {
            return ApiResponse::sendError('لا يمكنك إضافة ملاحظة لأنك لست الشخص الذي غيّر حالة الشكوى.', 403);
        }

        $history = ComplaintHistory::create([
            'complaint_id' => $complaint->id,
            'changed_by'   => $user->id,
            'old_status'   => $lastHistory->new_status,
            'new_status'   => $lastHistory->new_status,
            'notes'        => $note,
        ]);
        if ($complaint->user) {
            $this->firebase->sendToUser(
                $complaint->user->id,
                'تمت إضافة ملاحظة جديدة على شكواك',
                "الملاحظة: {$note}",
                ['complaint_id' => $complaint->id]
            );
        }
        return ApiResponse::sendResponse(200, 'تم إضافة الملاحظة وإرسال الإشعار بنجاح.', $history);
    }

    public function getComplaintHistory(int $complaintId)
    {
        $complaint = Complaint::with(['history.user', 'user', 'governmentEntity', 'type', 'attachments'])
            ->find($complaintId);
        if (!$complaint) {
            return ApiResponse::sendError('الشكوى غير موجودة.', 404);
        }
        return ApiResponse::sendResponse(200, 'تم جلب السجل الشكوى بنجاح.', new ComplaintHistoryResource($complaint));
    }
}

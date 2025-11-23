<?php

namespace App\Http\Controllers;

use App\Http\Services\AttachmentService;
use App\Http\Requests\attachmentsRequest;
use App\Http\Services\ComplaintStatusService;

class ComplaintAttachmentController extends Controller
{
    public function __construct(protected ComplaintStatusService $complaint_status_service, protected AttachmentService $attachment) {}

    public function addAttachments(attachmentsRequest $request, $complaintId)
    {
        $complaint = $this->attachment->getUserComplaint($complaintId);
        if (!$complaint) {
            return $this->attachment->sendJsonError('الشكوى غير موجودة أو لا تملك صلاحية الوصول إليها.', 404);
        }
        if ($this->attachment->isComplaintClosed($complaint)) {
            return $this->attachment->sendJsonError('لا يمكن رفع مرفقات لهذه الشكوى لأنها مرفوضة أو منجزة.', 403);
        }
        $attachments = $this->attachment->storeAttachments(
            $request->file('attachments', [])
        );

        if (!empty($attachments)) {
            $complaint->attachments()->attach($attachments);
        }
        $this->complaint_status_service->addAttachmentHistory($complaint->id, auth()->id());
        return response()->json([
            'message' => 'تم رفع المرفقات بنجاح.',
            'added_attachments_count' => count($attachments),
        ]);
    }
}

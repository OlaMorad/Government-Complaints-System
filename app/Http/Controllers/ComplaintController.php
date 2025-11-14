<?php

namespace App\Http\Controllers;

use App\Http\Requests\ComplaintRequest;
use Illuminate\Http\Request;
    use App\Http\Services\ComplaintService;
use App\Http\Services\AttachmentService;

class ComplaintController extends Controller
{

 public function store(ComplaintRequest $request, ComplaintService $complaintService, AttachmentService $attachmentService)
    {
        // رفع المرفقات وحفظ IDs
        $attachments = $attachmentService->storeAttachments($request->file('attachments', []));
        // إنشاء الشكوى
        $complaint = $complaintService->createComplaint($request->only(
            'complaint_type_id',
            'government_entity_id',
            'location_description',
            'problem_description'
        ), auth()->id());
        // ربط المرفقات بالشكوى
        if (!empty($attachments)) {
            $complaint->attachments()->attach($attachments);
        }

        return response()->json([
            'message' => 'تم تقديم الشكوى بنجاح',
            'reference_number' => $complaint->reference_number,
            'complaint_id' => $complaint->id
        ]);
    }
}




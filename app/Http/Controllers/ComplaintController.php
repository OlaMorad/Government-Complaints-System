<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
    use App\Http\Services\ComplaintService;
use App\Enums\ComplaintStatusEnum;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ComplaintRequest;
use App\Http\Services\AttachmentService;
use App\Http\Requests\ComplaintIdRequest;
use App\Http\Resources\ComplaintResource;
use App\Http\Requests\ComplaintReferenceRequest;
use App\Http\Requests\FilterComplaintStatusRequest;

class ComplaintController extends Controller
{
public function __construct(protected ComplaintService $complaintService,protected AttachmentService $attachmentService)
{
}
 public function store(ComplaintRequest $request)
    {
        // رفع المرفقات وحفظ IDs
        $attachments = $this->attachmentService->storeAttachments($request->file('attachments', []));
        // إنشاء الشكوى
        $complaint = $this->complaintService->createComplaint($request->only(
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
            'message' => 'تم تقديم شكواك بنجاح.',
            'reference_number' => $complaint->reference_number,
            'complaint_id' => $complaint->id
        ]);
    }

//     public function show_all_my_complaints(){
// return $this->complaintService->show_all_my_complaints();
//     }

    //في حال مررنا حالة يفلترها و اذا ما مررنا شي يرجع كل شكاوي المواطن
public function filter_complaint_status(FilterComplaintStatusRequest $request)
{

    return $this->complaintService->filter_complaint_status($request);

}

public function findMyComplaintByReference(ComplaintReferenceRequest $request)
{
    return $this->complaintService->findMyComplaintByReference($request->reference_number);

}

public function findMyComplaintById( ComplaintIdRequest $request)
{
        return $this->complaintService->findMyComplaintById($request->id);

}

}




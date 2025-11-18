<?php

namespace App\Http\Services;

use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ComplaintService
{
    public function __construct(protected UserActivityService $activity)
    {
    }
    public function createComplaint(array $data, int $userId): Complaint
    {
        $data['user_id'] = $userId;
        $data['reference_number'] = $this->generateReferenceNumber();
        $this->activity->add_activity($userId ,  'تم اضافة شكوى');
        return Complaint::create($data);
    }

    private function generateReferenceNumber(): string
    {
        return 'CMP-' . strtoupper(Str::random(10));
    }
    public function show_all_my_complaints()
    {
        $authUser = Auth::id();
        return Complaint::where('user_id', $authUser)->with('attachments')->get();
    }

    public function findMyComplaintByReference($referenceNumber)
    {
        $authUser = Auth::id();

        $complaint = Complaint::where('reference_number', $referenceNumber)
            ->where('user_id', $authUser)
            ->with('attachments')
            ->first();
        if (! $complaint) {
            return response()->json([
                'message' =>  'لا توجد شكوى بهذا الرقم المرجعي للمستخدم الحالي.'
            ], 404);
        }

        return response()->json($complaint);
    }

    public function findMyComplaintById($id)
    {

        $authUser = Auth::id();
        $complaint = Complaint::where('id', $id)
            ->where('user_id', $authUser)
            ->with('attachments')
            ->first();
        if (! $complaint) {
            return response()->json([
                'message' => 'لا توجد شكوى بهذا المعرف للمستخدم الحالي.'
            ], 404);
        }

        return response()->json([
            'message' =>'تم استرجاع الشكوى بنجاح.',
            'data' => $complaint
        ]);
    }
}

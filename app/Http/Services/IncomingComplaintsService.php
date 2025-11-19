<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\ComplaintDetailsResource;
use App\Http\Resources\IncomingComplaintResource;
use App\Models\Complaint;
use Illuminate\Support\Facades\Auth;

class IncomingComplaintsService
{
    public function getIncomingComplaints()
    {
        $user = Auth::user();
        $employee = $user->employee->load('governmentEntities.complaints');

        $lastEntityGovernment = $employee->governmentEntities()->latest()->first();

        if (!$lastEntityGovernment) {
            return ApiResponse::sendError('هذا الموظف غير مرتبط بأي جهة حكومية.', 404);
        }

        $employeeUserId = $user->id;

        $complaints = $lastEntityGovernment->complaints()
            ->with(['history' => function ($q) {
                $q->latest();
            }])
            ->get()
            ->filter(function ($complaint) use ($employeeUserId) {

                $lastHistory = $complaint->history->first();

                // إذا ما في تاريخ → تعرض طبيعي
                if (!$lastHistory) {
                    return true;
                }

                // إذا ليست قيد المعالجة → تعرض للجميع
                if ($lastHistory->new_status !== 'IN_PROGRESS') {
                    return true;
                }

                // إذا قيد المعالجة لكن الموظف الحالي هو يلي غير حالتها → تظهر له
                return $lastHistory->changed_by == $employeeUserId;
            })
            ->values();

        return ApiResponse::sendResponse(
            200,
            'تم جلب الشكاوي الواردة بنجاح.',
            IncomingComplaintResource::collection($complaints)
        );
    }

    public function getComplaintsDetails($complaints_id)
    {
        $complaint = Complaint::with(['user', 'governmentEntity', 'type', 'attachments'])
            ->find($complaints_id);

        if (!$complaint) {
            return ApiResponse::sendError('الشكوى غير موجود.', 404);
        }
        return ApiResponse::sendResponse(
            200,
            'تم جلب تفاصيل الشكوى بنجاح.',
            new ComplaintDetailsResource($complaint)
        );
    }
}

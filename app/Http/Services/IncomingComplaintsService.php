<?php

namespace App\Http\Services;

use App\Enums\ComplaintStatusEnum;
use App\Helpers\ApiResponse;
use App\Http\Resources\ComplaintDetailsResource;
use App\Http\Resources\IncomingComplaintResource;
use App\Models\Complaint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;

class IncomingComplaintsService
{
    public function getIncomingComplaints()
    {
        $user = Auth::user();
        $employee = $user->employee->load('governmentEntities.complaints');

        $lastEntityGovernment = $employee->governmentEntities()
            ->orderBy('employee_government_entities.created_at', 'desc')
            ->first();

        if (!$lastEntityGovernment) {
            return ApiResponse::sendError('هذا الموظف غير مرتبط بأي جهة حكومية.', 404);
        }

        $employeeUserId = $user->id;

        $complaints = $lastEntityGovernment->complaints()
            ->with('history')
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(
                fn($complaint) =>
                !$complaint->history->first() ||
                    $complaint->history->first()->new_status !== ComplaintStatusEnum::IN_PROGRESS->value ||
                    $complaint->history->first()->changed_by == $employeeUserId
            )
            ->values();

        $perPage = request()->get('per_page', 10);
        $page = request()->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $complaints->forPage($page, $perPage),
            $complaints->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        return ApiResponse::sendResponse(200,'تم جلب الشكاوي الواردة بنجاح.',
            IncomingComplaintResource::collection($paginated)->response()->getData(true));
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

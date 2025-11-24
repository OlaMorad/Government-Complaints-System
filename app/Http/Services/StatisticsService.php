<?php

namespace App\Http\Services;

use App\Enums\ComplaintStatusEnum;
use App\Helpers\ApiResponse;
use App\Models\Complaint;
use App\Models\User;
use App\Models\GovernmentEntity;
use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class StatisticsService
{
    /**
     * إحصائيات لوحة التحكم عند الأدمن
     */
    public function adminStats()
    {
        $data = [
            'total_complaints'       => Complaint::count(),
            'complaints_pending'     => Complaint::where('status', ComplaintStatusEnum::PENDING->value)->count(),
            'complaints_processing'  => Complaint::where('status', ComplaintStatusEnum::IN_PROGRESS->value)->count(),
            'complaints_rejected'    => Complaint::where('status', ComplaintStatusEnum::REJECTED->value)->count(),
            'complaints_completed'   => Complaint::where('status', ComplaintStatusEnum::COMPLETED->value)->count(),
            'total_users'            => User::role('المواطن')->count(),
            'total_employees'        => Employee::count(),
            'total_government_entities' => GovernmentEntity::count(),
        ];
        return ApiResponse::sendResponse(200, 'تم عرض الاحصائيات بنجاح', $data);
    }

    /**
     * إحصائيات جهة حكومية (حسب الموظف)
     */
    public function governmentStats()
    {
        $user = Auth::user();
        $employee = Employee::where('user_id', $user->id)->first();
        $lastGovernmentEntity = $employee->governmentEntities()
            ->orderBy('employee_government_entities.created_at', 'desc')
            ->first();

        if (!$lastGovernmentEntity) {
            return ApiResponse::sendError('الموظف غير مرتبط بأي جهة حكومية.', 404);
        }
        $govId = $lastGovernmentEntity->id;
        $data = [
            'total_complaints'       => Complaint::where('government_entity_id', $govId)->count(),
            'complaints_pending'     => Complaint::where('government_entity_id', $govId)->where('status', ComplaintStatusEnum::PENDING->value)->count(),
            'complaints_processing'  => Complaint::where('government_entity_id', $govId)->where('status', ComplaintStatusEnum::IN_PROGRESS->value)->count(),
            'complaints_rejected'    => Complaint::where('government_entity_id', $govId)->where('status', ComplaintStatusEnum::REJECTED->value)->count(),
            'complaints_completed'   => Complaint::where('government_entity_id', $govId)->where('status', ComplaintStatusEnum::COMPLETED->value)->count(),
        ];
        return ApiResponse::sendResponse(200, 'تم عرض الاحصائيات بنجاح', $data);
    }
}

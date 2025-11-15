<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
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
            return ApiResponse::sendError('This employee is not assigned to any government entity.', 404);
        }

        $complaints = $lastEntityGovernment->complaints()->get();

        return ApiResponse::sendResponse(200,'Incoming complaints fetched successfully.',IncomingComplaintResource::collection($complaints));
    }

    public function getComplaintsDetails($complaints_id)
    {
        $complaint=Complaint::find($complaints_id);
        if (!$complaint) {
            return ApiResponse::sendError('complaint not found.', 404);
        }
    }
}

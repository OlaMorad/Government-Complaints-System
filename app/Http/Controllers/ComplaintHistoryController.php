<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateComplaintStatusRequest;
use App\Http\Services\ComplaintStatusService;
use Illuminate\Http\Request;

class ComplaintHistoryController extends Controller
{
    public function __construct(protected ComplaintStatusService $complaint_status_service) {}

    public function toggleStatus($complaintId)
    {
        return $this->complaint_status_service->toggleStatus($complaintId);
    }

    public function updateStatus(UpdateComplaintStatusRequest $request, $id)
    {
        return $this->complaint_status_service->updateStatus($id, $request->validated()['status']);
    }
}

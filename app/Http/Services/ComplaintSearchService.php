<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\ComplaintDetailsResource;
use App\Http\Resources\IncomingComplaintResource;
use App\Models\Complaint;

class ComplaintSearchService
{
    /**
     * البحث عن الشكوى حسب reference number
     */
    public function searchByReference(string $referenceNumber)
    {
        $complaints = Complaint::where('reference_number', 'like', "%{$referenceNumber}%")
            ->with(['user', 'governmentEntity', 'type', 'attachments'])
            ->get();

        if ($complaints->isEmpty()) {
            return ApiResponse::sendError('لم يتم العثور على شكاوى بهذا الرقم المرجعي.', 404);
        }

        return ApiResponse::sendResponse(
            200,
            'تم العثور على الشكاوي بنجاح.',
            IncomingComplaintResource::collection($complaints)
        );
    }
}

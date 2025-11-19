<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Resources\IncomingComplaintResource;
use App\Http\Services\IncomingComplaintsService;
use App\Models\Complaint;
use Illuminate\Http\Request;

class IncomingComplaintsController extends Controller
{
    public function __construct(protected IncomingComplaintsService $incoming_complaints) {}

    public function all()
    {
        $complaints=Complaint::all();
        return ApiResponse::sendResponse(200,'تم عرض جميع الشكاوي بنجاح', IncomingComplaintResource::collection($complaints));
    }

    public function index()
    {
        return $this->incoming_complaints->getIncomingComplaints();
    }

    public function show($complaintId)
    {
        return $this->incoming_complaints->getComplaintsDetails($complaintId);
    }
}

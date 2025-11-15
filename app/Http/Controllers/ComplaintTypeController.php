<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Models\complaintType;

class ComplaintTypeController extends Controller
{
      public function index()
    {
        $entities = complaintType::all(['id', 'name']);
        return ApiResponse::sendResponse(200, 'Complaint types retrieved successfully.', $entities);
    }
}

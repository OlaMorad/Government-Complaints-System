<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\GovernmentEntity;
use Illuminate\Http\Request;

class GovernmentEntityController extends Controller
{
    public function index()
    {
        $entities = GovernmentEntity::all(['id', 'name']);
        return ApiResponse::sendResponse(200, 'تم استرجاع الجهات الحكومية بنجاح.', $entities);
    }
}

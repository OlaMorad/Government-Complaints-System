<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Http\Resources\EmployeeResource;
use App\Http\Services\EmployeeService;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function index()
    {
        $employees = Employee::all();
        return ApiResponse::sendResponse(200, 'تم عرض جميع الموظفين بنجاح', EmployeeResource::collection($employees));
    }

    public function createEmployee(RegisterRequest $request, $governmentEntityId)
    {
        return $this->employeeService->createEmployee($request->validated(), $governmentEntityId);
    }

    public function updateEmployee(UpdateEmployeeRequest $request, $employeeId)
    {
        return $this->employeeService->updateEmployee($employeeId, $request->validated());
    }
    public function deleteEmployee($employeeId)
    {
        return $this->employeeService->deleteEmployee($employeeId);
    }
}

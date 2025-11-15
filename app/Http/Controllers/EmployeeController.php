<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Services\EmployeeService;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $employeeService) {}

    public function createEmployee(RegisterRequest $request, $governmentEntityId)
    {
      return $this->employeeService->createEmployee($request->all(), $governmentEntityId);
    }

    public function updateGovernmentEntity(Request $request, $employeeId)
    {
        $request->validate([
            'government_entity_id' => 'required|integer|exists:government_entities,id',
        ]);
        return $this->employeeService->assignGovernmentEntityToEmployee($employeeId, $request->government_entity_id);
    }
}

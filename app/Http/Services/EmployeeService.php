<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Models\GovernmentEntity;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeService
{
    /**
     * إنشاء موظف وربطه بجهة حكومية
     */
    public function createEmployee(array $data, int $governmentEntityId)
    {
        // تحقق من وجود الجهة الحكومية
        $governmentEntity = GovernmentEntity::find($governmentEntityId);
        if (!$governmentEntity) {
            return ApiResponse::sendError('Government entity not found.', 404);
        }

        // إنشاء الموظف
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('Employee');

        $employee = Employee::create([
            'user_id' => $user->id
        ]);
        // ربط الموظف بالجهة الحكومية
        $employee->governmentEntities()->attach($governmentEntity->id);

        return ApiResponse::sendResponse(200, 'Employee created successfully.', new EmployeeResource($employee));
    }

    public function assignGovernmentEntityToEmployee(int $employeeId, int $governmentEntityId)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return ApiResponse::sendError('Employee not found.', 404);
        }

        $governmentEntity = GovernmentEntity::find($governmentEntityId);
        if (!$governmentEntity) {
            return ApiResponse::sendError('Government entity not found.', 404);
        }

        $employee->governmentEntities()->attach($governmentEntityId);

        return ApiResponse::sendResponse(
            200,
            'Government entity assigned to employee successfully.',
            new EmployeeResource($employee)
        );
    }
}

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
            return ApiResponse::sendError('الجهة الحكومية غير موجودة.', 404);
        }

        // إنشاء الموظف
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => Hash::make($data['password']),
        ]);
        $user->assignRole('الموظف');

        $employee = Employee::create([
            'user_id' => $user->id
        ]);
        // ربط الموظف بالجهة الحكومية
        $employee->governmentEntities()->attach($governmentEntity->id);

        return ApiResponse::sendResponse(200, 'تم إنشاء الموظف بنجاح.', new EmployeeResource($employee));
    }

    public function updateEmployee(int $employeeId, array $data)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return ApiResponse::sendError('الموظف غير موجود.', 404);
        }

        $user = $employee->user;

        $user->fill(collect($data)->only(['name', 'email', 'phone'])->toArray());

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        // تحديث الجهة الحكومية إذا تم إرسالها
        if (isset($data['government_entity_id'])) {
            $governmentEntity = GovernmentEntity::find($data['government_entity_id']);

            if (!$governmentEntity) {
                return ApiResponse::sendError('الجهة الحكومية غير موجودة.', 404);
            }

            $employee->governmentEntities()->attach($governmentEntity->id);
        }

        return ApiResponse::sendResponse(
            200,
            'تم تحديث بيانات الموظف بنجاح.',
            new EmployeeResource($employee)
        );
    }
    public function deleteEmployee(int $employeeId)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return ApiResponse::sendError('الموظف غير موجود.', 404);
        }

        $user = $employee->user;

        // أولاً نحذف ارتباط الموظف بالجهات الحكومية
        $employee->governmentEntities()->detach();

        $employee->delete();

        if ($user) {
            $user->delete();
        }

        return ApiResponse::sendResponse(200, 'تم حذف الموظف بنجاح.');
    }
}

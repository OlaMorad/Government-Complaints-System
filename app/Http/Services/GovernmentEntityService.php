<?php

namespace App\Http\Services;

use App\Helpers\ApiResponse;
use App\Models\GovernmentEntity;

class GovernmentEntityService
{
    public function store(array $data)
    {
        $entity = GovernmentEntity::create($data);

        return ApiResponse::sendResponse(201, 'تم إضافة الجهة الحكومية بنجاح.', $entity);
    }

    public function update(int $id, array $data)
    {
        $entity = GovernmentEntity::find($id);

        if (!$entity) {
            return ApiResponse::sendError('الجهة الحكومية غير موجودة.', 404);
        }

        $entity->update($data);

        return ApiResponse::sendResponse(200, 'تم تعديل الجهة الحكومية بنجاح.', $entity);
    }

    public function delete(int $id)
    {
        $entity = GovernmentEntity::find($id);

        if (!$entity) {
            return ApiResponse::sendError('الجهة الحكومية غير موجودة.', 404);
        }

        $entity->delete();

        return ApiResponse::sendResponse(200, 'تم حذف الجهة الحكومية بنجاح.');
    }
}

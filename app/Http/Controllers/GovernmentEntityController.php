<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\addGovernmentEntityRequest;
use App\Http\Services\GovernmentEntityService;
use App\Models\GovernmentEntity;
use Illuminate\Http\Request;

class GovernmentEntityController extends Controller
{
    public function __construct(protected GovernmentEntityService $service) {}

    public function index()
    {
        $entities = GovernmentEntity::all(['id', 'name']);
        return ApiResponse::sendResponse(200, 'تم استرجاع الجهات الحكومية بنجاح.', $entities);
    }
    public function store(addGovernmentEntityRequest $request)
    {
        return $this->service->store($request->validated());
    }

    public function update(addGovernmentEntityRequest $request, $id)
    {
        return $this->service->update($id, $request->validated());
    }

    public function destroy($id)
    {
        return $this->service->delete($id);
    }
}

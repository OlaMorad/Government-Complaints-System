<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'reference_number' => $this->reference_number,
            'user_name' => $this->user?->name,
            'government_entity' => $this->governmentEntity?->name,
            'complaint_type' => $this->type?->name,
            'status' => $this->status,
            'location_description' => $this->location_description,
            'problem_description' => $this->problem_description,
            'attachments' => $this->attachments()->get()->map(function ($att) {
                return [
                    'id' => $att->id,
                    'file_path' => $att->file_path,
                ];
            }),
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i'),
        ];
    }
}

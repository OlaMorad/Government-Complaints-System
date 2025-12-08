<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ComplaintResource extends JsonResource
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

            'government_entity' => $this->governmentEntity?->name,

            'complaint_type' => $this->type?->name,

            'location_description' => $this->location_description,
            'problem_description' => $this->problem_description,
            'reference_number' => $this->reference_number,
            'status' => $this->status,

            'attachments' => $this->whenLoaded('attachments'),

            'created_at' => $this->created_at,
        ];    }
}

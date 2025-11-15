<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IncomingComplaintResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'user_name'         => $this->user ? $this->user->name : null,
            'reference_number'  => $this->reference_number,
            'status'            => $this->status ? $this->status : null,
            'complaint_type'    => $this->type->name,
            'created_at'        => $this->created_at ? $this->created_at->format('Y-m-d H:i') : null,
            'updated_at'        => $this->updated_at ? $this->updated_at->format('Y-m-d H:i') : null,
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enums\ComplaintStatusEnum;
use Illuminate\Foundation\Http\FormRequest;

class FilterComplaintStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'status' => [
                'required',
                'in:' . implode(',', array_column(ComplaintStatusEnum::cases(), 'value')),
            ],
        ];

    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEmployeeRequest extends FormRequest
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
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $this->employee,
            'phone' => 'sometimes|string',
            'password' => 'sometimes|string|confirmed',
            'government_entity_id' => 'sometimes|exists:government_entities,id',
        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'يجب إدخال بريد إلكتروني صالح.',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل.',
            'password.confirmed' => 'تأكيد كلمة المرور غير متطابق.',
            'government_entity_id.exists' => 'الجهة الحكومية المحددة غير موجودة.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddComplaintNoteRequest extends FormRequest
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
            'note' => 'required|string|min:3|max:2000',
        ];
    }
    public function messages(): array
    {
        return [
            'note.required' => 'يرجى كتابة ملاحظاتك على هذه الشكوى',
            'note.min'      => 'يجب أن تكون الملاحظة 3 أحرف على الأقل.',
            'note.max'      => 'يجب أن لا تتجاوز الملاحظة 2000 حرف على الأكثر'
        ];
    }
}

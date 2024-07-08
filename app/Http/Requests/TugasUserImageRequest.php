<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TugasUserImageRequest extends FormRequest
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
            'tugas_user_id' => 'required|exists:tugas_users,id',
            'image' => 'required',
        ];
    }
}

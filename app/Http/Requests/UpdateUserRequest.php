<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'full_name' => 'required|string|min:1|max:255',
            'nickname' => 'required|string|min:1|max:255',
            'birth' => 'required|string|min:1|max:255',
            'address' => 'required|string|min:1|max:255',
            'shift' => 'required|string|min:1|max:255',
            'password' => 'required|string|min:1|max:255',
        ];
    }
}

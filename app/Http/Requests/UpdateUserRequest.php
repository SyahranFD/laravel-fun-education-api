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
            'full_name' => 'string|min:1|max:255',
            'nickname' => 'string|min:1|max:255',
            'email' => 'string|min:1|max:255',
            'birth' => 'string|min:1|max:255',
            'address' => 'string|min:1|max:255',
            'shift' => 'string|min:1|max:255',
            'gender' => 'string|min:1|max:255',
            'password' => 'string|min:1|max:255',
            'is_verified' => 'boolean',
            'is_verified_email' => 'boolean',
            'is_graduated' => 'boolean',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OtpCheckRequest extends FormRequest
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
            'email' => 'required|string|min:1|max:255',
            'otp' => 'required|string|min:1|max:255',
            'reset_password' => 'boolean',
        ];
    }
}
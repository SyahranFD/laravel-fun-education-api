<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TugasRequest extends FormRequest
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
            'shift' => 'required|string|min:1|max:255',
            'category' => 'required|string|min:1|max:255',
            'title' => 'required|string|min:1|max:255',
            'description' => 'required|string|min:1|max:255',
            'deadline' => 'required|date',
            'status' => 'string|min:1|max:255',
            'grade' => 'integer',
        ];
    }
}

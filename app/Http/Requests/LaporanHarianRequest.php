<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LaporanHarianRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'note' => 'string|max:1000',
            'created_at' => '',
            'activity_1' => 'required|string|min:1|max:255',
            'activity_2' => 'required|string|min:1|max:255',
            'activity_3' => 'required|string|min:1|max:255',
            'activity_4' => 'required|string|min:1|max:255',
            'activity_5' => 'required|string|min:1|max:255',
            'activity_6' => 'required|string|min:1|max:255',
            'activity_7' => 'required|string|min:1|max:255',
            'activity_8' => 'required|string|min:1|max:255',
            'activity_9' => 'required|string|min:1|max:255',
            'activity_10' => 'required|string|min:1|max:255',
        ];
    }
}

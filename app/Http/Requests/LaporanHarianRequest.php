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
            'user_id' => 'exists:users,id',
            'note' => 'string|min:1|max:1000',
            'permission' => 'string|min:1|max:255',
            'created_at' => '',
            'activity_1' => 'string|min:1|max:255',
            'activity_2' => 'string|min:1|max:255',
            'activity_3' => 'string|min:1|max:255',
            'activity_4' => 'string|min:1|max:255',
            'activity_5' => 'string|min:1|max:255',
            'activity_6' => 'string|min:1|max:255',
            'activity_7' => 'string|min:1|max:255',
            'activity_8' => 'string|min:1|max:255',
            'activity_9' => 'string|min:1|max:255',
            'activity_10' => 'string|min:1|max:255',
        ];
    }
}

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
            'user_id' => 'required',
            'datang_tepat_pada_waktunya' => 'required|string|min:1|max:255',
            'berpakaian_rapi' => 'required|string|min:1|max:255',
            'berbuat_baik_dengan_teman' => 'required|string|min:1|max:255',
            'mau_menolong_dan_berbagi_dengan_teman' => 'required|string|min:1|max:255',
            'merapikan_alat_belajar_dan_mainan_sendiri' => 'required|string|min:1|max:255',
            'menyelesaikan_tugas' => 'required|string|min:1|max:255',
            'membaca' => 'required|string|min:1|max:255',
            'menulis' => 'required|string|min:1|max:255',
            'dikte' => 'required|string|min:1|max:255',
            'keterampilan' => 'required|string|min:1|max:255',
        ];
    }
}

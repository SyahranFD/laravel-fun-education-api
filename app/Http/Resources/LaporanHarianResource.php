<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LaporanHarianResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'datang_tepat_pada_waktunya' => $this->datang_tepat_pada_waktunya,
            'berpakaian_rapi' => $this->berpakaian_rapi,
            'berbuat_baik_dengan_teman' => $this->berbuat_baik_dengan_teman,
            'mau_menolong_dan_berbagi_dengan_teman' => $this->mau_menolong_dan_berbagi_dengan_teman,
            'merapikan_alat_belajar_dan_mainan_sendiri' => $this->merapikan_alat_belajar_dan_mainan_sendiri,
            'menyelesaikan_tugas' => $this->menyelesaikan_tugas,
            'membaca' => $this->membaca,
            'menulis' => $this->menulis,
            'dikte' => $this->dikte,
            'keterampilan' => $this->keterampilan,
        ];
    }
}

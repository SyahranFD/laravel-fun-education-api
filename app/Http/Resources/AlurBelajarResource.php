<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlurBelajarResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $tahap_a = $this->tahap === 'A' ? true : false;
        $tahap_b = $this->tahap === 'B' ? true : false;
        $tahap_c = $this->tahap === 'C' ? true : false;

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'tahap' => $this->tahap,
            'tahap_a' => $tahap_a || $tahap_b || $tahap_c,
            'tahap_b' => $tahap_b || $tahap_c,
            'tahap_c' => $tahap_c,
        ];
    }
}

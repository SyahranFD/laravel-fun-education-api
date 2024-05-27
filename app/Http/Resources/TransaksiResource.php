<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransaksiResource extends JsonResource
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
            'jenis' => $this->jenis,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'tanggal' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

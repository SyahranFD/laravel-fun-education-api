<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laraindo\TanggalFormat;

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
            'nominal' => number_format($this->nominal, 0, '.', '.'),
            'keterangan' => $this->keterangan,
            'tanggal' => TanggalFormat::DateIndo($this->created_at->format('Y/m/d'), 'l, j F Y'),
        ];
    }
}

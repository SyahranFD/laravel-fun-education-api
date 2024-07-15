<?php

namespace App\Http\Resources;

use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TugasCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tersedia' => Tugas::where('shift', $this->shift)->where('status', 'tersedia')->count(),
            'ditutup' => Tugas::where('shift', $this->shift)->where('status', 'ditutup')->count(),
            'diarsipkan' => Tugas::where('shift', $this->shift)->where('status', 'diarsipkan')->count(),
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Models\Activity;
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
            'activity' => Activity::find($this->activity_id)->name ?? null,
            'grade' => $this->grade,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class TugasUserResource extends JsonResource
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
            'tugas_id' => $this->tugas_id,
            'full_name' => $this->user->full_name,
            'profile_picture' => $this->user->profile_picture,
            'status' => $this->status,
            'note' => $this->note,
            'grade' => $this->grade,
            'created_at' => $this->created_at,
            'images' => TugasUserImageResource::collection($this->tugasUserImages),
        ];
    }
}

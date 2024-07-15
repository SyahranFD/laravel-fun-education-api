<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TugasResource extends JsonResource
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
            'shift' => $this->shift,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'point' => $this->point,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at->format('Y-m-d'),
            'images' => TugasImageResource::collection($this->tugasImages),
        ];
    }
}

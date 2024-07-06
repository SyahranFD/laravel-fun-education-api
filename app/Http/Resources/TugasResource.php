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
            'user_id' => $this->user_id,
            'tugas_category_id' => $this->tugas_category_id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'grade' => $this->grade,
            'parent_note' => $this->parent_note,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}

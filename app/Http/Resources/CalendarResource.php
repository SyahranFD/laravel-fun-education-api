<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CalendarResource extends JsonResource
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
            'category' => $this->calendarCategory->name,
            'title' => $this->title,
            'description' => $this->description,
            'date' => $this->date,
            'color' => $this->calendarCategory->color,
            'file' => CalendarFileResource::collection($this->calendarFiles),
        ];
    }
}

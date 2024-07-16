<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlbumResource extends JsonResource
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
            'name' => $this->name,
            'desc' => $this->desc,
            'cover' => $this->cover,
            'created_at' => $this->created_at,
            'gallery_count' => $this->gallery->count(),
            'gallery' => GalleryResource::collection($this->gallery)
        ];
    }
}

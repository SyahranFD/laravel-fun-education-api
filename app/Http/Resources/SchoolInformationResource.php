<?php

namespace App\Http\Resources;

use App\Models\SchoolInformationDesc;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SchoolInformationResource extends JsonResource
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
            'title' => $this->title,
            'description' => SchoolInformationDesc::collection($this->schoolInformationDesc),
        ];
    }
}

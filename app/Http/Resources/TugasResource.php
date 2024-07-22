<?php

namespace App\Http\Resources;

use App\Models\TugasUser;
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
        $user = auth()->user();
        $statusTugasUser = null;

        if ($user) {
            $tugasUser = TugasUser::where('tugas_id', $this->id)->where('user_id', $user->id)->first();
            if ($tugasUser) {
                $statusTugasUser = $tugasUser->status;
            }
        }

        return [
            'id' => $this->id,
            'shift' => $this->shift,
            'category' => $this->category,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_tugas_user' => $statusTugasUser,
            'point' => $this->point,
            'deadline' => $this->deadline,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'images' => TugasImageResource::collection($this->tugasImages),
        ];
    }
}

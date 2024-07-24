<?php

namespace App\Http\Resources;

use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'full_name' => $this->user->full_name,
            'point' => $this->total_points,
            'is_user' => $this->user_id === auth()->id(),
            'rank' => $this->rank,
        ];
    }
}

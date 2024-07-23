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
            'point' => Leaderboard::where('user_id', $this->user_id)->sum('point'),
            'is_user' => $this->user_id === auth()->id(),
            'rank' => $this->rank,
        ];
    }
}

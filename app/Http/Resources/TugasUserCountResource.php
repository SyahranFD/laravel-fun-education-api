<?php

namespace App\Http\Resources;

use App\Models\Tugas;
use App\Models\TugasUser;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TugasUserCountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = auth()->user();
        $tugasUserIds = TugasUser::where('user_id', $user->id)->pluck('tugas_id');
        $terbaruCount = Tugas::whereNotIn('id', $tugasUserIds)->where('shift', $user->shift)->where('status', 'tersedia')->count();

        return [
            'terbaru' => $terbaruCount,
            'diperiksa' => TugasUser::where('user_id', auth()->user()->id)->where('status', 'diperiksa')->count(),
            'selesai' => TugasUser::where('user_id', auth()->user()->id)->where('status', 'selesai')->count(),
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaderboardRequest;
use App\Http\Resources\LeaderboardResource;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $now = now();

        if ($type === 'weekly') {
            $startDate = $now->copy()->subWeek();
            $endDate = $now;
        } elseif ($type === 'monthly') {
            $startDate = $now->copy()->subMonth();
            $endDate = $now;
        } else {
            $startDate = null;
            $endDate = null;
        }

        $query = Leaderboard::select('user_id', DB::raw('SUM(point) as total_points'))
            ->groupBy('user_id')
            ->orderBy('total_points', 'desc');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $leaderboards = $query->get();

        $rank = 1;
        $actualRank = 1;
        $prevPoints = null;

        foreach ($leaderboards as $leaderboard) {
            if ($prevPoints != $leaderboard->total_points) {
                $rank = $actualRank;
                $actualRank++;
            }

            $leaderboard->rank = $rank;
            $prevPoints = $leaderboard->total_points;
        }

        return LeaderboardResource::collection($leaderboards);
    }
}

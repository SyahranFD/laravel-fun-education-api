<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaderboardRequest;
use App\Http\Resources\LeaderboardResource;
use App\Models\Leaderboard;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->query('type');
        $shift = $request->query('shift');

        if ($type === 'weekly') {
            $startDate = Carbon::now()->subDays(7);
            $endDate = Carbon::now();
        } elseif ($type === 'monthly') {
            $startDate = Carbon::now()->subDays(30);
            $endDate = Carbon::now();
        } else {
            $startDate = null;
            $endDate = null;
        }

        $query = Leaderboard::select('user_id', DB::raw('SUM(point) as total_points'))
            ->groupBy('user_id')
            ->orderBy('total_points', 'desc');

        if ($shift) {
            $query->whereHas('user', function ($query) use ($shift) {
                $query->where('shift', $shift);
            });
        }

        $query->whereHas('user', function ($query) {
            $query->where('is_verified', true)
                  ->where('is_verified_email', true);
        });

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

    public function point()
    {
        $user = auth()->user();
        $point = Leaderboard::where('user_id', $user->id)->sum('point');
        $point = number_format($point, 0, '.', '.');

        return response(['point' => $point,]);
    }
}

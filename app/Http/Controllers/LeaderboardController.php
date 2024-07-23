<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeaderboardRequest;
use App\Models\Leaderboard;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LeaderboardController extends Controller
{
    public function store(LeaderboardRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $leaderboardData = $request->all();
        do {
            $leaderboardData['id'] = 'leaderboard-'.Str::uuid();
        } while (Leaderboard::where('id', $leaderboardData['id'])->exists());

        $leaderboard = Leaderboard::create($leaderboardData);

        return $this->resStoreData($leaderboard);
    }

    public function index()
    {
        $leaderboard = [];
    }
}

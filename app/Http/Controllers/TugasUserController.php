<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasUserRequest;
use App\Http\Resources\TugasUserResource;
use App\Models\Leaderboard;
use App\Models\Tugas;
use App\Models\TugasUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class TugasUserController extends Controller
{
    public function store(TugasUserRequest $request)
    {
        $request->validated();
        $user = auth()->user();

        $tugasUserData = $request->all();
        $tugasUserData['user_id'] = $user->id;
        do {
            $tugasUserData['id'] = 'tugas-user-'.Str::uuid();
        } while (Tugas::where('id', $tugasUserData['id'])->exists());

        $tugasUser = TugasUser::create($tugasUserData);
        $tugasUser = new TugasUserResource($tugasUser);

        $tugas = Tugas::find($tugasUserData['tugas_id']);
        $admin = User::where('role', 'admin')->first();
        $notification = 'Admin tidak memiliki fcm_token atau fcm_token tidak valid';
        if ($admin->fcm_token) {
            $notification = $this->notification($admin->id, 'Tugas Baru Untuk Diperiksa', 'Tugas '. strtolower($tugas->title) .' dari '. strtolower($user->nickname) . ' siap untuk diperiksa', '/detail-mark-page', $tugasUser->tugas_id, $tugasUser->id);
        }

        return $this->resStoreData($tugasUser, $notification);
    }

    public function index()
    {
        return TugasUserResource::collection(TugasUser::orderBy('created_at', 'desc')->get());
    }

    public function show($id)
    {
        $tugasUser = TugasUser::find($id);
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        return new TugasUserResource($tugasUser);
    }

    public function showByTugasId($tugasId)
    {
        $tugasUser = TugasUser::where('tugas_id', $tugasId)->orderBy('created_at', 'desc')->get();
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        return TugasUserResource::collection($tugasUser);
    }

    public function showCurrent($tugasId)
    {
        $user = auth()->user();
        $tugasUser = TugasUser::where('tugas_id', $tugasId)->where('user_id', $user->id)->first();
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        return new TugasUserResource($tugasUser);
    }

    public function showStatistic(Request $request)
    {
        $amount = $request->query('amount');
        $userId = $request->query('user_id');
        if (! $userId) {
            $userId = auth()->user()->id;
        }

        $currentDate = Carbon::now();
        $statistics = [];
        $bottomTitle = [];
        $count = 1;
        $noDataCount = 0;

        while ($count <= $amount) {
            $tugasUser = TugasUser::where('user_id', $userId)
                ->whereDate('created_at', $currentDate)
                ->where('status', 'Selesai')
                ->get();

            if (!$tugasUser->isEmpty()) {
                $totalPoint = $tugasUser->sum('grade');
                $statistics[] = [
                    'date' => $currentDate->toDateString(),
                    'title' => $tugasUser->first()->tugas->title,
                    'total_point' => $totalPoint,
                    'spot' => $count,
                ];

                $bottomTitle[$count]['date'] = $currentDate->format('d/m/y');
                $bottomTitle[$count]['case'] = $count;
                $count++;
                $noDataCount = 0;
            } else {
                $noDataCount++;
                if ($noDataCount >= 60) {
                    break;
                }
            }

            $currentDate->subDay();
        }

        $statistics = array_reverse($statistics);
        $bottomTitle = array_reverse($bottomTitle);

        $currentSpot = 1;
        foreach ($statistics as &$statistic) {
            $statistic['spot'] = $currentSpot++;
        }

        $currentCase = 1;
        foreach ($bottomTitle as &$title) {
            $title['case'] = $currentCase++;
        }

        $interval = ceil(count($bottomTitle) / 5);

        $bottomTitle = array_filter($bottomTitle, function($key) use ($interval) {
            return $key % $interval == 0;
        }, ARRAY_FILTER_USE_KEY);

        $bottomTitle = array_values($bottomTitle);

        return response([
            'total_data' => $count - 1,
            'data' => $statistics,
            'bottom_title' => $bottomTitle,
        ]);
    }

    public function sendGrade(Request $request, $id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $request->validate(['grade' => 'required|integer']);

        $tugasUser = TugasUser::find($id);
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        $tugasUserData = $request->all();
        $tugasUserData['status'] = 'Selesai';
        $tugasUser->update($tugasUserData);

        $leaderboardId = 0;
        do {
            $leaderboardId = 'leaderboard-'.Str::uuid();
        } while (Leaderboard::where('id', $leaderboardId)->exists());
        Leaderboard::create(['id' => $leaderboardId, 'user_id' => $tugasUser->user_id, 'tugas_user_id' => $tugasUser->id, 'point' => $request->grade]);


        return new TugasUserResource($tugasUser);
    }

    public function update(TugasUserRequest $request, $tugasId)
    {
        $request->validated();
        $user = auth()->user();

        $tugasUser = TugasUser::where('tugas_id', $tugasId)->first();
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        $tugasUserData = $request->all();
        $tugasUser->update($tugasUserData);
        $tugasUser = new TugasUserResource($tugasUser);

        return $this->resUpdateData($tugasUser);
    }

    public function destroy($id)
    {
        $tugasUser = TugasUser::find($id);
        if (! $tugasUser) {
            return $this->resDataNotFound('Tugas User');
        }

        $tugasUser->delete();

        return $this->resDataDeleted("Tugas User");
    }

    public function notification($userId, $title, $body, $route, $tugasId, $tugasUserId)
    {
        $FcmToken = User::find($userId)->fcm_token;
        $tugas = Tugas::find($tugasId);
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ])->withData([
            'route' => $route,
            'tugas_id' => $tugasId,
            'tugas_user_id' => $tugasUserId,
            'shift' => $tugas->shift,
        ]);

        Firebase::messaging()->send($message);
        return $message;
    }
}

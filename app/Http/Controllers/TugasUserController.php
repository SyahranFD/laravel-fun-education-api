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
            $notification = $this->notification($admin, 'Tugas Baru Untuk Diperiksa', 'Tugas '. strtolower($tugas->title) .' dari '. strtolower($user->nickname) . ' siap untuk diperiksa', '/detail-mark-page', $tugas, $tugasUser);
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

    public function showStatisticNew(Request $request)
    {
        $type = $request->query('type');
        $userId = $request->query('user_id');
        if (! $userId) {
            $userId = auth()->user()->id;
        }

        $statistics = [];
        $bottomTitle = [];

        if ($type == 'weekly') {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $tugasUser = TugasUser::where('user_id', $userId)
                    ->whereDate('created_at', $date)
                    ->get();

                $totalPoint = min($tugasUser->sum('grade'), 100);
                $statistics[] = [
                    'date' => $date->toDateString(),
                    'total_point' => $totalPoint,
                ];

                $bottomTitle[] = [
                    'date' => $date->format('d-m'),
                    'case' => 6 - $i,
                ];
            }
        } elseif ($type == 'monthly') {
            for ($i = 30; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $tugasUser = TugasUser::where('user_id', $userId)
                    ->whereDate('created_at', $date)
                    ->get();

                $totalPoint = min($tugasUser->sum('grade'), 100);
                $statistics[] = [
                    'date' => $date->toDateString(),
                    'total_point' => $totalPoint,
                ];

                if (in_array($i, [0, 10, 20, 30])) {
                    $bottomTitle[] = [
                        'date' => $date->format('d-m'),
                        'case' => 30 - $i,
                    ];
                }
            }
        }

        return response([
            'data' => $statistics,
            'bottom_title' => $bottomTitle,
        ]);
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
                $groupedTugasUser = $tugasUser->groupBy('tugas.title');

                foreach ($groupedTugasUser as $title => $groupedData) {
                    $totalPoint = $groupedData->sum('grade');
                    $statistics[] = [
                        'date' => $currentDate->toDateString(),
                        'title' => $title,
                        'total_point' => $totalPoint,
                        'spot' => $count,
                    ];

                    $bottomTitle[$count]['date'] = $currentDate->format('d/m/y');
                    $bottomTitle[$count]['case'] = $count;
                    $count++;
                }

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

        $currentSpot = 0;
        foreach ($statistics as &$statistic) {
            $statistic['spot'] = $currentSpot++;
        }

        $currentCase = 0;
        foreach ($bottomTitle as &$title) {
            $title['case'] = $currentCase++;
        }

        if ($amount == 5 || $amount == 10 || $amount == 21 || $amount == 30) {
            $indices = [];
            if ($amount == 5) { $indices = [0, 1, 2, 3, 4];
            } elseif ($amount == 10) { $indices = [0, 3, 6, 9];
            } elseif ($amount == 21) { $indices = [0, 5, 10, 15, 20];
            } elseif ($amount == 30) { $indices = [0, 10, 20, 29]; }

            $bottomTitle = array_intersect_key($bottomTitle, array_flip($indices));

            foreach ($indices as $i) {
                if (empty($bottomTitle[$i]['date'])) {
                    $bottomTitle[$i]['date'] = '';
                    $bottomTitle[$i]['case'] = $i;
                }
            }
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
        $tugasUser = new TugasUserResource($tugasUser);

        $leaderboardId = 0;
        do {
            $leaderboardId = 'leaderboard-'.Str::uuid();
        } while (Leaderboard::where('id', $leaderboardId)->exists());
        Leaderboard::create(['id' => $leaderboardId, 'user_id' => $tugasUser->user_id, 'tugas_user_id' => $tugasUser->id, 'point' => $request->grade]);

        $user = User::find($tugasUser->user_id);
        $tugas = Tugas::find($tugasUser->tugas_id);
        $notification = 'Admin tidak memiliki fcm_token atau fcm_token tidak valid';
        if ($user->fcm_token) {
            $notification = $this->notification($user, 'Tugas Selesai Dinilai', 'Tugas '. strtolower($tugas->title) .' sudah selesai dinilai, mohon untuk dilihat', '/detail-tugas-page', $tugas, $tugasUser);
        }

        return $this->resStoreData($tugasUser, $notification);
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

    public function notification($user, $title, $body, $route, $tugas, $tugasUser)
    {
        try {
            $message = CloudMessage::fromArray([
                'token' => $user->fcm_token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
            ])->withData([
                'route' => $route,
                'tugas_id' => $tugas->id,
                'tugas_user_id' => $tugasUser->id,
                'shift' => $tugas->shift,
            ]);

            Firebase::messaging()->send($message);
            return $message;
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            // If a NotFound exception is thrown, do nothing and continue
        }
    }
}

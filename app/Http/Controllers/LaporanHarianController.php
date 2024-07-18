<?php

namespace App\Http\Controllers;

use App\Http\Requests\LaporanHarianRequest;
use App\Http\Resources\LaporanHarianResource;
use App\Models\Activity;
use App\Models\LaporanHarian;
use App\Models\LaporanHarianNote;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LaporanHarianController extends Controller
{
    public function store(LaporanHarianRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $activityList = Activity::orderBy('id')->get();
        $note = $request->get('note');
        $laporanHarianList = [];
        $totalPoint = 0;

        foreach ($activityList as $index => $activity) {
            $laporanHarianData = [];
            $laporanHarianData['activity_id'] = $activity->id;
            $laporanHarianData['user_id'] = $request->get('user_id');
            $laporanHarianData['grade'] = $request->get('activity_'.($index+1));
            $laporanHarianData['note'] = $request->get('note');
            do {
                $laporanHarianData['id'] = 'laporan-harian-'.Str::uuid();
            } while (LaporanHarian::where('id', $laporanHarianData['id'])->exists());

            if ($laporanHarianData['grade'] == 'A') {
                $laporanHarianData['point'] = 10;
                $totalPoint += 10;
            } elseif ($laporanHarianData['grade'] == 'B') {
                $laporanHarianData['point'] = 4;
                $totalPoint += 4;
            } elseif ($laporanHarianData['grade'] == 'C') {
                $laporanHarianData['point'] = 3;
                $totalPoint += 3;
            }

            $laporanHarian = LaporanHarian::create($laporanHarianData);
            $laporanHarianList[] = $laporanHarian;
        }

        $laporanHarianList = LaporanHarianResource::collection($laporanHarianList);

        return response([
            'data' => $laporanHarianList,
            'note' => $note ?? '',
            'total_point' => $totalPoint,
        ], 201);
    }


    public function index()
    {
        return LaporanHarianResource::collection(LaporanHarian::all());
    }

    public function showById($id)
    {
        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        return new LaporanHarianResource($laporanHarian);
    }

    public function showCurrent(Request $request)
    {
        $user = auth()->user();
        $date = $request->query('date');
        $laporanHarian = LaporanHarian::where('user_id', $user->id)
            ->whereDate('created_at', $date)
            ->orderBy('activity_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('activity_id')
            ->map(function ($grouped) {
                return $grouped->first();
            });
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $totalPoint = $laporanHarian->sum('point');
        $note = $laporanHarian->first()->note ?? null;

        return response([
            'data' => LaporanHarianResource::collection($laporanHarian),
            'note' => $note,
            'total_point' => $totalPoint,
        ], 200);
    }

    public function showFilter(Request $request)
    {
        $userId = $request->query('userId');
        $date = $request->query('date');
        $laporanHarian = LaporanHarian::where('user_id', $userId)
            ->whereDate('created_at', $date)
            ->orderBy('activity_id')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('activity_id')
            ->map(function ($grouped) {
                return $grouped->first();
            });
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $totalPoint = $laporanHarian->sum('point');
        $note = $laporanHarian->first()->note ?? null;

        return response([
            'data' => LaporanHarianResource::collection($laporanHarian),
            'note' => $note,
            'total_point' => $totalPoint,
        ], 200);
    }

    public function showCurrentPoint(Request $request)
    {
        $type = $request->query('type');

        if ($type == 'weekly') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            $laporanHarian = LaporanHarian::where('user_id', auth()->user()->id)
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->get();

            $points = ['monday_point' => 0, 'tuesday_point' => 0, 'wednesday_point' => 0, 'thursday_point' => 0, 'friday_point' => 0, 'saturday_point' => 0, 'sunday_point' => 0,];

            foreach ($laporanHarian as $laporan) {
                $dayOfWeek = $laporan->created_at->dayOfWeek;
                switch ($dayOfWeek) {
                    case Carbon::MONDAY:
                        $points['monday_point'] += $laporan->point;
                        break;
                    case Carbon::TUESDAY:
                        $points['tuesday_point'] += $laporan->point;
                        break;
                    case Carbon::WEDNESDAY:
                        $points['wednesday_point'] += $laporan->point;
                        break;
                    case Carbon::THURSDAY:
                        $points['thursday_point'] += $laporan->point;
                        break;
                    case Carbon::FRIDAY:
                        $points['friday_point'] += $laporan->point;
                        break;
                    case Carbon::SATURDAY:
                        $points['saturday_point'] += $laporan->point;
                        break;
                    case Carbon::SUNDAY:
                        $points['sunday_point'] += $laporan->point;
                        break;
                }
            }

            return response(['data' => $points], 200);
        }

        if ($type == 'monthly') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            $laporanHarian = LaporanHarian::where('user_id', auth()->user()->id)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->get();

            $points = ['week1_point' => 0, 'week2_point' => 0, 'week3_point' => 0, 'week4_point' => 0,];

            foreach ($laporanHarian as $laporan) {
                $weekOfMonth = $laporan->created_at->weekOfMonth;
                switch ($weekOfMonth) {
                    case 1:
                        $points['week1_point'] += $laporan->point;
                        break;
                    case 2:
                        $points['week2_point'] += $laporan->point;
                        break;
                    case 3:
                        $points['week3_point'] += $laporan->point;
                        break;
                    case 4:
                        $points['week4_point'] += $laporan->point;
                        break;
                }
            }

            return response(['data' => $points]);
        }
    }

    public function update(LaporanHarianRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $laporanHarian->update($request->all());

        return new LaporanHarianResource($laporanHarian);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $laporanHarian = LaporanHarian::find($id);
        if (! $laporanHarian) {
            return $this->resDataNotFound('Laporan Harian');
        }

        $laporanHarian->delete();

        return $this->resDataDeleted('Laporan Harian');
    }

    public function notification($id)
    {
        $FcmToken = User::find($id)->fcm_token;
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => 'Laporan Harian',
                'body' => 'Anda memiliki laporan harian yang belum dibaca.',
            ],
        ]);

        Firebase::messaging()->send($message);
    }
}

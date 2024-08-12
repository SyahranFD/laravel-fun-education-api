<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasRequest;
use App\Http\Resources\TugasCountResource;
use App\Http\Resources\TugasResource;
use App\Http\Resources\TugasUserCountResource;
use App\Models\Tugas;
use App\Models\TugasUser;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

class TugasController extends Controller
{
    public function store(TugasRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tugasData = $request->all();
        do {
            $tugasData['id'] = 'tugas-'.Str::uuid();
        } while (Tugas::where('id', $tugasData['id'])->exists());

        $tugas = Tugas::create($tugasData);
        $tugas = new TugasResource($tugas);

        $users = User::whereNotNull('fcm_token')->where('role', 'student')->where('shift', $tugas->shift)->get();
        foreach ($users as $user) {
            $this->notification($user, 'Tugas Baru', 'Anda memiliki tugas baru yang siap untuk dikerjakan', '/home-page');
        }

        return $this->resStoreData($tugas);
    }

    public function index(Request $request)
    {
        $shift = $request->query('shift');
        $status = $request->query('status');

        $query = Tugas::query();

        if ($shift) {
            $query->where('shift', $shift);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $tugasData = $query->orderBy('created_at', 'desc')->get();

        return TugasResource::collection($tugasData);
    }

    public function showStatusCount(Request $request)
    {
        $shift = $request->query('shift');
        $tugas = Tugas::where('shift', $shift)->first();

        return new TugasCountResource($tugas);
    }

    public function showCurrentStatusTugasUserCount()
    {
        $user = auth()->user();
        return new TugasUserCountResource($user);
    }

    public function showById($id)
    {
        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        return new TugasResource($tugas);
    }

    public function showCurrent(Request $request)
    {
        $status = $request->query('status');
        $user = auth()->user();
        if (! $user) {
            return $this->resUserNotFound();
        }

        $query = Tugas::where('shift', $user->shift)->orderBy('created_at', 'desc');

        if (! $status) {
            $query->where('status', 'Tersedia');
        }

        if ($status) {
            if ($status == 'Terbaru') {
                $tugasId = TugasUser::where('user_id', $user->id)->pluck('tugas_id');
                $query->whereNotIn('id', $tugasId);
            } else {
                $tugasId = TugasUser::where('user_id', $user->id)->where('status', $status)->pluck('tugas_id');
                $query->whereIn('id', $tugasId);
            }
        }

        $tugas = $query->get();

        return TugasResource::collection($tugas);
    }

    public function update(TugasRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        $tugas->update($request->all());

        return new TugasResource($tugas);
    }

    public function updateStatus(Request $request, $id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $request->validate(['status' => 'required|string']);

        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        $tugas->update(['status' => $request->status]);

        return new TugasResource($tugas);
    }

    public function destroy($id)
    {
        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        $tugas->delete();

        return $this->resDataDeleted('Tugas');
    }

    public function notification($user, $title, $body, $route)
    {
        $FcmToken = $user->fcm_token;
        $message = CloudMessage::fromArray([
            'token' => $FcmToken,
            'notification' => [
                'title' => $title,
                'body' => $body,
            ],
        ])->withData([
            'route' => $route,
        ]);

        Firebase::messaging()->send($message);
        return $message;
    }
}

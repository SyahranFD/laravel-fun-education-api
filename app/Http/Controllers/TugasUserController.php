<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasUserRequest;
use App\Http\Resources\TugasUserResource;
use App\Models\Tugas;
use App\Models\TugasUser;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

        return $this->resStoreData($tugasUser);
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

    public function sendGrade(Request $request, $id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $request->validate(['grade' => 'required|integer']);

        $tugasUser = TugasUser::find($id);
        if (! $tugasUser) {
            return $this->resNotFound();
        }

        $tugasUserData = $request->all();
        $tugasUserData['status'] = 'Selesai';
        $tugasUser->update($tugasUserData);

        return new TugasUserResource($tugasUser);
    }

    public function update(TugasUserRequest $request, $tugasId)
    {
        $request->validated();
        $user = auth()->user();

        $tugasUser = TugasUser::where('tugas_id', $tugasId)->first();
        if (! $tugasUser) {
            return $this->resNotFound();
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
            return $this->resNotFound();
        }

        $tugasUser->delete();

        return $this->resDataDeleted("Tugas User");
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\TugasRequest;
use App\Http\Resources\TugasResource;
use App\Models\Tugas;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

        $tugas = tugas::create($tugasData);
        $tugas = new tugasResource($tugas);

        return $this->resStoreData($tugas);
    }

    public function index()
    {
        return TugasResource::collection(Tugas::with('tugasImages')->get());
    }

    public function showById($id)
    {
        $tugas = Tugas::with('tugasImages')->find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        return new TugasResource($tugas);
    }

    public function showCurrent()
    {
        $tugas = auth()->user()->tugas;
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        return TugasResource::collection($tugas->with('tugasImages'));
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

    public function updateGrade(Request $request, $id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $request->validate(['grade' => 'required|integer']);

        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        $tugas->update(['grade' => $request->grade]);

        return new TugasResource($tugas);
    }

    public function sendTugas(Request $request, $id)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|string|min:1|max:255',
            'parent_note' => 'string|min:1|max:255',
        ]);

        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        $tugas->update([
            'status' => $request->status,
            'parent_note' => $request->parent_note ?? '',
        ]);

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
}

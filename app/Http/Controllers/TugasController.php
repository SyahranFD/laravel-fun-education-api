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

        $tugasData = $query->get();

        return TugasResource::collection($tugasData);
    }

    public function showById($id)
    {
        $tugas = Tugas::find($id);
        if (! $tugas) {
            return $this->resDataNotFound('Tugas');
        }

        return new TugasResource($tugas);
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
}

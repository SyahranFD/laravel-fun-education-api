<?php

namespace App\Http\Controllers;

use App\Http\Requests\CatatanDaruratRequest;
use App\Http\Resources\CatatanDaruratResource;
use App\Models\CatatanDarurat;
use Illuminate\Support\Str;

class CatatanDaruratController extends Controller
{
    public function store(CatatanDaruratRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $catatanDaruratData = $request->all();
        do {
            $catatanDaruratData['id'] = 'catatan-darurat-'.Str::uuid();
        } while (CatatanDarurat::where('id', $catatanDaruratData['id'])->exists());

        $catatanDarurat = CatatanDarurat::create($catatanDaruratData);
        $catatanDarurat = new CatatanDaruratResource($catatanDarurat);

        return $this->resStoreData($catatanDarurat);
    }

    public function index()
    {
        return CatatanDaruratResource::collection(CatatanDarurat::all());
    }

    public function showById($id)
    {
        $catatanDarurat = CatatanDarurat::find($id);
        if (! $catatanDarurat) {
            return $this->resDataNotFound('Catatan Darurat');
        }

        return new CatatanDaruratResource($catatanDarurat);
    }

    public function show()
    {
        $catatanDarurat = CatatanDarurat::where('is_deleted', false)->latest()->first();
        if (! $catatanDarurat) {
            return $this->resDataNotFound('Catatan Darurat');
        }

        return new CatatanDaruratResource($catatanDarurat);
    }

    public function update(CatatanDaruratRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $catatanDarurat = CatatanDarurat::find($id);
        if (! $catatanDarurat) {
            return $this->resDataNotFound('Catatan Darurat');
        }

        $catatanDaruratData = $request->all();
        $catatanDarurat->update($catatanDaruratData);
        $catatanDarurat = new CatatanDaruratResource($catatanDarurat);

        return $this->resStoreData($catatanDarurat);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $catatanDarurat = CatatanDarurat::find($id);
        if (! $catatanDarurat) {
            return $this->resDataNotFound('Catatan Darurat');
        }

        $catatanDarurat->update(['is_deleted' => true]);

        return $this->resDataDeleted('Catatan Darurat');
    }
}

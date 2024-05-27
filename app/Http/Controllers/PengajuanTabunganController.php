<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengajuanTabunganRequest;
use App\Http\Resources\PengajuanTabunganResource;
use App\Models\PengajuanTabungan;
use Illuminate\Support\Str;

class PengajuanTabunganController extends Controller
{
    public function store(PengajuanTabunganRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $pengajuanTabunganData = $request->all();
        $pengajuanTabunganData['status'] = 'Menunggu';
        do {
            $pengajuanTabunganData['id'] = 'pengajuan-tabungan-'.Str::uuid();
        } while (PengajuanTabungan::where('id', $pengajuanTabunganData['id'])->exists());

        $pengajuanTabungan = PengajuanTabungan::create($pengajuanTabunganData);
        $pengajuanTabungan = new PengajuanTabunganResource($pengajuanTabungan);

        return $this->resStoreData($pengajuanTabungan);
    }

    public function index()
    {
        return PengajuanTabunganResource::collection(PengajuanTabungan::all());
    }

    public function showById($id)
    {
        $pengajuanTabungan = PengajuanTabungan::find($id);
        if (! $pengajuanTabungan) {
            return $this->resDataNotFound('Pengajuan Tabungan');
        }

        return new PengajuanTabunganResource($pengajuanTabungan);
    }

    public function showCurrent()
    {
        $pengajuanTabungan = auth()->user()->pengajuanTabungan;
        if (! $pengajuanTabungan) {
            return $this->resDataNotFound('Pengajuan Tabungan');
        }

        return PengajuanTabunganResource::collection($pengajuanTabungan);
    }

    public function update(PengajuanTabunganRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $pengajuanTabungan = PengajuanTabungan::find($id);
        if (! $pengajuanTabungan) {
            return $this->resDataNotFound('Pengajuan Tabungan');
        }

        $pengajuanTabungan->update($request->all());

        return new PengajuanTabunganResource($pengajuanTabungan);
    }

    public function destroy($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $pengajuanTabungan = PengajuanTabungan::find($id);
        if (! $pengajuanTabungan) {
            return $this->resDataNotFound('Pengajuan Tabungan');
        }

        $pengajuanTabungan->delete();

        return $this->resDataDeleted('Pengajuan Tabungan');
    }
}

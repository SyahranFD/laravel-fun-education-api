<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftMasukRequest;
use App\Http\Resources\ShiftMasukResource;
use App\Models\ShiftMasuk;
use Illuminate\Support\Str;

class ShiftMasukController extends Controller
{
    public function store(ShiftMasukRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }
        
        if (ShiftMasuk::where('shift_masuk', $request->shift_masuk)->exists()) {
            return response(['message' => 'Shift Masuk Already Exists'], 409);
        }

        $shiftMasuk = ShiftMasuk::create($request->all());
        $shiftMasuk = new ShiftMasukResource($shiftMasuk);

        return $this->resStoreData($shiftMasuk);
    }

    public function index()
    {
        return ShiftMasukResource::collection(ShiftMasuk::orderBy('id', 'asc')->get());
    }

    public function showById($id)
    {
        $shiftMasuk = ShiftMasuk::find($id);
        if (! $shiftMasuk) {
            return $this->resDataNotFound('Shift Masuk');
        }

        return new ShiftMasukResource($shiftMasuk);
    }

    public function showCurrent()
    {
        $shiftMasuk = auth()->user()->shiftMasuk;
        if (! $shiftMasuk) {
            return $this->resDataNotFound('Shift Masuk');
        }

        return new ShiftMasukResource($shiftMasuk);
    }

    public function update(ShiftMasukRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $shiftMasuk = ShiftMasuk::find($id);
        if (! $shiftMasuk) {
            return $this->resDataNotFound('Shift Masuk');
        }

        $shiftMasuk->update($request->all());

        return new ShiftMasukResource($shiftMasuk);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $shiftMasuk = ShiftMasuk::find($id);
        if (! $shiftMasuk) {
            return $this->resDataNotFound('Shift Masuk');
        }

        $shiftMasuk->delete();

        return $this->resDataDeleted('Shift Masuk');
    }
}

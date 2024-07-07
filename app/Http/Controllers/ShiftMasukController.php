<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShiftMasukRequest;
use App\Http\Resources\ShiftMasukResource;
use App\Models\ShiftMasuk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ShiftMasukController extends Controller
{
    public function store(ShiftMasukRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $shiftMasukData = $request->all();
        do {
            $shiftMasukData['id'] = 'shift-masuk-'.Str::uuid();
        } while (ShiftMasuk::where('id', $shiftMasukData['id'])->exists());

        $shiftMasuk = ShiftMasuk::create($shiftMasukData);
        $shiftMasuk = new ShiftMasukResource($shiftMasuk);

        return $this->resStoreData($shiftMasuk);
    }

    public function index(Request $request)
    {
        $shift = $request->query('shift');
        $shiftMasuk = [];
        if ($shift) {
            $shiftMasuk = ShiftMasuk::where('shift_masuk', $shift)->get();
        } else {
            $shiftMasuk = ShiftMasuk::all();
        }
        return ShiftMasukResource::collection($shiftMasuk);
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

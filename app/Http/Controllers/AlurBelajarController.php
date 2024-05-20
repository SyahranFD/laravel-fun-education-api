<?php

namespace App\Http\Controllers;

use App\Http\Requests\AlurBelajarRequest;
use App\Http\Resources\AlurBelajarResource;
use App\Models\AlurBelajar;
use Illuminate\Support\Str;

class AlurBelajarController extends Controller
{
    public function store(AlurBelajarRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajarData = $request->all();
        do {
            $alurBelajarData['id'] = 'alur-belajar-'.Str::uuid();
        } while (AlurBelajar::where('id', $alurBelajarData['id'])->exists());

        $alurBelajar = AlurBelajar::create($alurBelajarData);
        $alurBelajar = new AlurBelajarResource($alurBelajar);

        return $this->resStoreData($alurBelajar);
    }

    public function index()
    {
        return AlurBelajarResource::collection(AlurBelajar::all());
    }

    public function showById($id)
    {
        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        return new AlurBelajarResource($alurBelajar);
    }

    public function showCurrent()
    {
        $showCurrent = auth()->user()->showCurrent;
        if (! $showCurrent) {
            return $this->resDataNotFound('Show Current');
        }

        return new AlurBelajarResource($showCurrent);
    }

    public function update(AlurBelajarRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        $alurBelajarData = $request->all();
        $alurBelajar->update($alurBelajarData);

        return new AlurBelajarResource($alurBelajar);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $alurBelajar = AlurBelajar::find($id);
        if (! $alurBelajar) {
            return $this->resDataNotFound('Alur Belajar');
        }

        $alurBelajar->delete();

        return $this->resDataDeleted('Alur Belajar');
    }
}

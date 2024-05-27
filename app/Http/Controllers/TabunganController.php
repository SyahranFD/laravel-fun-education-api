<?php

namespace App\Http\Controllers;

use App\Http\Requests\TabunganRequest;
use App\Http\Resources\TabunganResource;
use App\Models\Tabungan;
use Illuminate\Support\Str;
use Number;

class TabunganController extends Controller
{
    public function store(TabunganRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tabunganData = $request->all();
        do {
            $tabunganData['id'] = 'tabungan-'.Str::uuid();
        } while (tabungan::where('id', $tabunganData['id'])->exists());

        $tabungan = tabungan::create($tabunganData);
        $tabungan['tabungan'] = number_format($tabungan->tabungan, 0, '.', '.');
        $tabungan = new tabunganResource($tabungan);

        return $this->resStoreData($tabungan);
    }

    public function index()
    {
        $tabungan = Tabungan::all();
        foreach($tabungan as $tabungans) {
            $tabungans['tabungan'] = number_format($tabungans->tabungan, 0, '.', '.');
        }
        return TabunganResource::collection($tabungan);
    }

    public function showById($id)
    {
        $tabungan = Tabungan::find($id);
        if (! $tabungan) {
            return $this->resDataNotFound('Tabungan');
        }
        $tabungan['tabungan'] = number_format($tabungan->tabungan, 0, '.', '.');
        return new TabunganResource($tabungan);
    }

    public function showCurrent()
    {
        $tabungan = auth()->user()->tabungan;
        if (! $tabungan) {
            return $this->resDataNotFound('Tabungan');
        }
        $tabungan['tabungan'] = number_format($tabungan->tabungan, 0, '.', '.');
        return new TabunganResource($tabungan);
    }

    public function update(TabunganRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $tabungan = Tabungan::find($id);
        if (! $tabungan) {
            return $this->resDataNotFound('Tabungan');
        }

        $tabungan->update($request->all());
        $tabungan['tabungan'] = number_format($tabungan->tabungan, 0, '.', '.');
        return new TabunganResource($tabungan);
    }

    public function destroy($id)
    {
        $tabungan = Tabungan::find($id);
        if (! $tabungan) {
            return $this->resDataNotFound('Tabungan');
        }

        $tabungan->delete();

        return $this->resDataDeleted('Tabungan');
    }
}

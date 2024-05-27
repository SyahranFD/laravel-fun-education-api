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
        $tabungan = auth()->user()->tabungan->first();
        if (! $tabungan) {
            return $this->resDataNotFound('Tabungan');
        }
        $tabungan['tabungan'] = number_format($tabungan->tabungan, 0, '.', '.');

        $transaksi = auth()->user()->transaksi;
        if (! $transaksi) {
            return $this->resDataNotFound('Transaksi');
        }

        $pemasukanTerakhir = number_format($transaksi->where('jenis', 'pemasukan')->sortByDesc('created_at')->first()['nominal'] ?? 0, 0, '.', '.');
        $pengeluaranTerakhir = number_format($transaksi->where('jenis', 'pengeluaran')->sortByDesc('created_at')->first()['nominal'] ?? 0, 0, '.', '.');

        $responseBody = array_merge(
            $tabungan->toArray(),
            [
                'pemasukan_terakhir' => $pemasukanTerakhir,
                'pengeluaran_terakhir' => $pengeluaranTerakhir,
            ]
        );

        unset($responseBody['created_at']);
        unset($responseBody['updated_at']);

        return response()->json(['data' => $responseBody]);
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

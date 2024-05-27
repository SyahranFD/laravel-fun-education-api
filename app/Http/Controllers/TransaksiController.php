<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransaksiRequest;
use App\Http\Resources\TransaksiResource;
use App\Models\Transaksi;
use Illuminate\Support\Str;
use Laraindo\TanggalFormat;

class TransaksiController extends Controller
{
    public function store(TransaksiRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transaksiData = $request->all();
        do {
            $transaksiData['id'] = 'shift-masuk-'.Str::uuid();
        } while (Transaksi::where('id', $transaksiData['id'])->exists());

        $transaksi = Transaksi::create($transaksiData);
        $transaksi = new TransaksiResource($transaksi);

        return $this->resStoreData($transaksi);
    }

    public function index()
    {
        return TransaksiResource::collection(Transaksi::all());
    }

    public function showById($id)
    {
        $transaksi = Transaksi::find($id);
        if (! $transaksi) {
            return $this->resDataNotFound('Transaksi');
        }

        return new TransaksiResource($transaksi);
    }

    public function showCurrent()
    {
        $transaksi = auth()->user()->transaksi;
        if (! $transaksi) {
            return $this->resDataNotFound('Transaksi');
        }
        $formattedTransaksi = [];
        foreach ($transaksi as $transaksis) {
            $formattedTransaksi[] = [
                'id' => $transaksis->id,
                'user_id' => $transaksis->user_id,
                'nominal' => $transaksis->nominal,
                'jenis' => $transaksis->jenis,
                'keterangan' => $transaksis->keterangan,
                'tanggal' => TanggalFormat::DateIndo($transaksis->created_at->format('Y/m/d'), 'l, j F Y'),
                'date' => $transaksis->created_at->format('d F Y H:i:s'),
            ];
        }

        $groupedTransaksi = collect($formattedTransaksi)
        ->sortByDesc(function ($item) {
            return date('Ym', strtotime($item['date']));
        })
        ->groupBy(function ($item) {
            return TanggalFormat::DateIndo($item['date'], 'F Y');
        })
        ->map(function ($item, $key) {
            return [
                'date' => $key,
                'transaksi' => $item,
            ];
        })
        ->values();

        return response()->json(['data' => $groupedTransaksi]);
    }

    public function update(TransaksiRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transaksi = Transaksi::find($id);
        if (! $transaksi) {
            return $this->resDataNotFound('Transaksi');
        }
        $transaksi->update($request->all());

        return new TransaksiResource($transaksi);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transaksi = Transaksi::find($id);
        if (! $transaksi) {
            return $this->resDataNotFound('Transaksi');
        }
        $transaksi->delete();

        return $this->resDataDeleted('Transaksi');
    }
}
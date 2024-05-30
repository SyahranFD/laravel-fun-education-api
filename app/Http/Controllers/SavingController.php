<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingRequest;
use App\Http\Resources\SavingResource;
use App\Models\Saving;
use Illuminate\Support\Str;
use Number;

class SavingController extends Controller
{
    public function store(SavingRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $savingData = $request->all();
        do {
            $savingData['id'] = 'saving-'.Str::uuid();
        } while (Saving::where('id', $savingData['id'])->exists());

        $saving = Saving::create($savingData);
        $saving = new SavingResource($saving);

        return $this->resStoreData($saving);
    }

    public function index()
    {
        return SavingResource::collection(Saving::all());
    }

    public function showById($id)
    {
        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        return new SavingResource($saving);
    }

    public function showCurrent()
    {
        $saving = auth()->user()->savings;
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }
        $saving['saving'] = number_format($saving->saving, 0, '.', '.');

        $transaction = auth()->user()->transaction;
        if (! $transaction) {
            return $this->resDataNotFound('Transaction');
        }

        $pemasukanTerakhir = number_format($transaction->where('jenis', 'pemasukan')->sortByDesc('created_at')->first()['nominal'] ?? 0, 0, '.', '.');
        $pengeluaranTerakhir = number_format($transaction->where('jenis', 'pengeluaran')->sortByDesc('created_at')->first()['nominal'] ?? 0, 0, '.', '.');

        $responseBody = array_merge(
            $saving->toArray(),
            [
                'pemasukan_terakhir' => $pemasukanTerakhir,
                'pengeluaran_terakhir' => $pengeluaranTerakhir,
            ]
        );

        unset($responseBody['created_at']);
        unset($responseBody['updated_at']);

        return response()->json(['data' => $responseBody]);
    }

    public function update(SavingRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        $saving->update($request->all());

        return new SavingResource($saving);
    }

    public function destroy($id)
    {
        $saving = Saving::find($id);
        if (! $saving) {
            return $this->resDataNotFound('saving');
        }

        $saving->delete();

        return $this->resDataDeleted('saving');
    }
}

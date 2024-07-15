<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Saving;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Laraindo\TanggalFormat;

class TransactionController extends Controller
{
    public function store(TransactionRequest $request)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transactionData = $request->all();
        do {
            $transactionData['id'] = 'shift-masuk-'.Str::uuid();
        } while (Transaction::where('id', $transactionData['id'])->exists());

        $saving = Saving::where('user_id', $request->user_id)->first();
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }

        if ($request->category === 'income') {
            $saving->update([
                'saving' => $saving->saving + $request->amount,
            ]);
        } elseif ($request->category === 'outcome') {
            $saving->update([
                'saving' => $saving->saving - $request->amount,
            ]);
        } else {
            return response(['message' => 'Transaction Category Is Not Valid'], 400);
        }

        $transaction = Transaction::create($transactionData);
        $transaction = new TransactionResource($transaction);

        return $this->resStoreData($transaction);
    }

    public function index()
    {
        return TransactionResource::collection(Transaction::all());
    }

    public function showById($id)
    {
        $transaction = Transaction::find($id);
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }

        return new TransactionResource($transaction);
    }

    public function showCurrent()
    {
        $transaction = auth()->user()->transaction;
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }
        $formattedtransaction = [];
        foreach ($transaction as $transactions) {
            $formattedtransaction[] = [
                'id' => $transactions->id,
                'user_id' => $transactions->user_id,
                'amount' => number_format($transactions->amount, 0, '.', '.'),
                'category' => $transactions->category,
                'desc' => $transactions->desc,
                'date' => $transactions->created_at->format('d-m-Y'),
            ];
        }

        $groupedtransaction = collect($formattedtransaction)
        ->sortByDesc(function ($item) {
            return date('Ym', strtotime($item['date']));
        })
        ->groupBy(function ($item) {
            return date('m-Y', strtotime($item['date']));
        })
        ->map(function ($item, $key) {
            return [
                'month' => $key,
                'transaction' => $item,
            ];
        })
        ->values();

        return response()->json(['data' => $groupedtransaction]);
    }

    public function showByUserId($userId)
    {
        $transaction = Transaction::where('user_id', $userId)->get();
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }
        $formattedtransaction = [];
        foreach ($transaction as $transactions) {
            $formattedtransaction[] = [
                'id' => $transactions->id,
                'user_id' => $transactions->user_id,
                'amount' => number_format($transactions->amount, 0, '.', '.'),
                'category' => $transactions->category,
                'desc' => $transactions->desc,
                'date' => $transactions->created_at->format('d-m-Y'),
            ];
        }

        $groupedtransaction = collect($formattedtransaction)
        ->sortByDesc(function ($item) {
            return date('Ym', strtotime($item['date']));
        })
        ->groupBy(function ($item) {
            return date('m-Y', strtotime($item['date']));
        })
        ->map(function ($item, $key) {
            return [
                'month' => $key,
                'transaction' => $item,
            ];
        })
        ->values();

        return response()->json(['data' => $groupedtransaction]);
    }

    public function update(TransactionRequest $request, $id)
    {
        $request->validated();
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transaction = Transaction::find($id);
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }

        $oldAmount = $transaction->amount;
        $transaction->update($request->all());
        $newAmount = $transaction->amount;

        $saving = Saving::where('user_id', $transaction->user_id)->first();
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }

        $selisih = $newAmount - $oldAmount;
        if ($selisih > 0) {
            $saving->update([
                'saving' => $saving->saving + $selisih,
            ]);
        } elseif ($selisih < 0) {
            $saving->update([
                'saving' => $saving->saving - abs($selisih),
            ]);
        }

        return new TransactionResource($transaction);
    }

    public function delete($id)
    {
        $admin = auth()->user();
        if (! $admin->isAdmin()) {
            return $this->resUserNotAdmin();
        }

        $transaction = Transaction::find($id);
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }
        $amount = $transaction->amount;
        $transaction->delete();

        $saving = Saving::where('user_id', $transaction->user_id)->first();
        if (! $saving) {
            return $this->resDataNotFound('Saving');
        }
        $saving->update([
            'saving' => $saving->saving - $amount,
        ]);

        return $this->resDataDeleted('transaction');
    }
}

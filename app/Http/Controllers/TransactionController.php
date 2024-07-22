<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Saving;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function showCurrent(Request $request)
    {
        $transaction = Transaction::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc');
        $month = $request->query('month');
        $limit = $request->query('limit');
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }

        if ($month) {
            $months = ['Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,];

            if (!isset($months[$month])) {
                return response(['message' => 'Invalid month'], 400);
            }

            $transaction = $transaction->whereMonth('created_at', $months[$month]);
        }

        if ($limit) {
            $transaction = $transaction->limit($limit);
        }

        $transaction = $transaction->get();
        $total_income = $transaction->where('category', 'income')->sum('amount');
        $total_outcome = $transaction->where('category', 'outcome')->sum('amount');

        return response([
            'total_income' => number_format($total_income, 0, '.', '.'),
            'total_outcome' => number_format($total_outcome, 0, '.', '.'),
            'data' => TransactionResource::collection($transaction),
        ]);
    }

    public function showByUserId(Request $request, $userId)
    {
        $transaction = Transaction::where('user_id', $userId);
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }
        $month = $request->query('month');
        $limit = $request->query('limit');
        if (! $transaction) {
            return $this->resDataNotFound('transaction');
        }

        if ($month) {
            $months = ['Januari' => 1, 'Februari' => 2, 'Maret' => 3, 'April' => 4, 'Mei' => 5, 'Juni' => 6, 'Juli' => 7, 'Agustus' => 8, 'September' => 9, 'Oktober' => 10, 'November' => 11, 'Desember' => 12,];

            if (!isset($months[$month])) {
                return response(['message' => 'Invalid month'], 400);
            }

            $transaction = $transaction->whereMonth('created_at', $months[$month]);
        }

        if ($limit) {
            $transaction = $transaction->limit($limit);
        }

        $transaction = $transaction->get();
        $total_income = $transaction->where('category', 'income')->sum('amount');
        $total_outcome = $transaction->where('category', 'outcome')->sum('amount');

        return response([
            'total_income' => number_format($total_income, 0, '.', '.'),
            'total_outcome' => number_format($total_outcome, 0, '.', '.'),
            'data' => TransactionResource::collection($transaction),
        ]);
    }

    public function showStatistic(Request $request)
    {
        $type = $request->query('type');
        $userId = $request->query('user_id');

        if ($type == 'weekly') {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();

            if (! auth()->user()->isAdmin()) {
                $transactionIncome = Transaction::where('user_id', auth()->user()->id)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('category', 'income')->get();
                $transactionOutcome = Transaction::where('user_id', auth()->user()->id)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('category', 'outcome')->get();
            } else {
                $transactionIncome = Transaction::where('user_id', $userId)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('category', 'income')->get();
                $transactionOutcome = Transaction::where('user_id', $userId)->whereBetween('created_at', [$startOfWeek, $endOfWeek])->where('category', 'outcome')->get();
                if ($transactionIncome->isEmpty() && $transactionOutcome->isEmpty()) {
                    return $this->resDataNotFound('Transaction');
                }
            }


            $amount = ['monday_income' => 0, 'monday_outcome' => 0, 'tuesday_income' => 0, 'tuesday_outcome' => 0, 'wednesday_income' => 0, 'wednesday_outcome' => 0, 'thursday_income' => 0, 'thursday_outcome' => 0, 'friday_income' => 0, 'friday_outcome' => 0, 'saturday_income' => 0, 'saturday_outcome' => 0, 'sunday_income' => 0, 'sunday_outcome' => 0,];

            foreach ($transactionIncome as $income) {
                $dayOfWeek = $income->created_at->dayOfWeek;
                switch ($dayOfWeek) {
                    case Carbon::MONDAY:
                        $amount['monday_income'] += $income->amount;
                        break;
                    case Carbon::TUESDAY:
                        $amount['tuesday_income'] += $income->amount;
                        break;
                    case Carbon::WEDNESDAY:
                        $amount['wednesday_income'] += $income->amount;
                        break;
                    case Carbon::THURSDAY:
                        $amount['thursday_income'] += $income->amount;
                        break;
                    case Carbon::FRIDAY:
                        $amount['friday_income'] += $income->amount;
                        break;
                    case Carbon::SATURDAY:
                        $amount['saturday_income'] += $income->amount;
                        break;
                    case Carbon::SUNDAY:
                        $amount['sunday_income'] += $income->amount;
                        break;
                }
            }

            foreach ($transactionOutcome as $outcome) {
                $dayOfWeek = $outcome->created_at->dayOfWeek;
                switch ($dayOfWeek) {
                    case Carbon::MONDAY:
                        $amount['monday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::TUESDAY:
                        $amount['tuesday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::WEDNESDAY:
                        $amount['wednesday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::THURSDAY:
                        $amount['thursday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::FRIDAY:
                        $amount['friday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::SATURDAY:
                        $amount['saturday_outcome'] += $outcome->amount;
                        break;
                    case Carbon::SUNDAY:
                        $amount['sunday_outcome'] += $outcome->amount;
                        break;
                }
            }

            return response(['data' => $amount], 200);
        }

        if ($type == 'monthly') {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();

            if (! auth()->user()->isAdmin()) {
                $transactionIncome = Transaction::where('user_id', auth()->user()->id)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('category', 'income')->get();
                $transactionOutcome = Transaction::where('user_id', auth()->user()->id)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('category', 'outcome')->get();
            } else {
                $transactionIncome = Transaction::where('user_id', $userId)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('category', 'income')->get();
                $transactionOutcome = Transaction::where('user_id', $userId)->whereBetween('created_at', [$startOfMonth, $endOfMonth])->where('category', 'outcome')->get();
                if ($transactionIncome->isEmpty() && $transactionOutcome->isEmpty()) {
                    return $this->resDataNotFound('Transaction');
                }
            }

            $amount = ['week1_income' => 0, 'week1_outcome' => 0, 'week2_income' => 0, 'week2_outcome' => 0, 'week3_income' => 0, 'week3_outcome' => 0, 'week4_income' => 0, 'week4_outcome' => 0,];

            foreach ($transactionIncome as $income) {
                $weekOfMonth = $income->created_at->weekOfMonth;
                switch ($weekOfMonth) {
                    case 1:
                        $amount['week1_income'] += $income->amount;
                        break;
                    case 2:
                        $amount['week2_income'] += $income->amount;
                        break;
                    case 3:
                        $amount['week3_income'] += $income->amount;
                        break;
                    case 4:
                        $amount['week4_income'] += $income->amount;
                        break;
                }
            }

            foreach ($transactionOutcome as $outcome) {
                $weekOfMonth = $outcome->created_at->weekOfMonth;
                switch ($weekOfMonth) {
                    case 1:
                        $amount['week1_outcome'] += $outcome->amount;
                        break;
                    case 2:
                        $amount['week2_outcome'] += $outcome->amount;
                        break;
                    case 3:
                        $amount['week3_outcome'] += $outcome->amount;
                        break;
                    case 4:
                        $amount['week4_outcome'] += $outcome->amount;
                        break;
                }
            }

            return response(['data' => $amount]);
        }
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

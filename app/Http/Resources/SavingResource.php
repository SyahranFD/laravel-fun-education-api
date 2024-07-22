<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'saving' => number_format($this->saving, 0, '.', '.'),
            'last_income' => number_format(Transaction::where('category', 'income')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->first()->amount ?? 0, 0, '.', '.'),
            'last_outcome' => number_format(Transaction::where('category', 'outcome')->where('user_id', $this->user_id)->orderBy('created_at', 'desc')->first()->amount ?? 0, 0, '.', '.'),
        ];
    }
}

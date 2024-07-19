<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Laraindo\dateFormat;

class TransactionResource extends JsonResource
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
            'category' => $this->category,
            'amount' => number_format($this->amount, 0, '.', '.'),
            'desc' => $this->desc,
            'date' => $this->created_at->format('Y-m-d')
        ];
    }
}

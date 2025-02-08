<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WithdrawResource extends JsonResource
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
            'created_at' => $this->created_at->format('d F, Y'),
            'bill_no' => '#'.str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'amount' => number_format($this->amount, 2, '.', ','),
            'invoice_url' => null,
            'status' => $this->status,
            'reason' => $this->reason,
        ];
    }
}

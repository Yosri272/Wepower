<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AddressResource extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->phone,
            'area' => $this->area,
            'flat_no' => $this->flat_no,
            'address_type' => $this->address_type,
            'address_line' => $this->address_line,
            'address_line2' => $this->address_line2,
            'post_code' => $this->post_code,
            'is_default' => (bool) $this->is_default,
            'langitude' => $this->langitude,
            'latitude' => $this->latitude,
        ];
    }
}

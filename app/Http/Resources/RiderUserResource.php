<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderUserResource extends JsonResource
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
            'first_name' => $this->name,
            'last_name' => $this->last_name,
            'phone' => $this->phone,
            'email' => $this->email,
            'is_active' => (bool) $this->is_active,
            'profile_photo' => $this->thumbnail,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'driving_lience' => $this->driving_lience,
            'vehicle_type' => $this->vehicle_type,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DriversResource extends JsonResource
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
          'address' => $this->address,
          'date_of_birth' => $this->date_of_birth,
          'type' => $this->type,
          'status' => $this->status,
          'market' => $this->market,
          'registration_date' => $this->registration_date,
          'expiry_date' => $this->expiry_date,
          'account_number' => $this->account_number,
          'account_name' => $this->account_name,
          'api_domain' => $this->api_domain,
          'order_value' => $this->order_value,
          'vehicle_number' => $this->vehicle_number,
          'subscription_value' => $this->subscription_value,
          'img_profile' => $this->img_profile_path,

      ];
    }
}

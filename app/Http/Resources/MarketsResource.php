<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarketsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       $data= [
           'id' => $this->id,
           'name' => $this->name,
           'phone' => $this->phone,
           'address' => $this->address,
           'type' => $this->type,
           'status' => $this->status,
           'market' => $this->market,
           'registration_date' => $this->registration_date,
           'expiry_date' => $this->expiry_date,
           'account_number' => $this->account_number,
           'account_name' => $this->account_name,
           'name_branch' => $this->name_branch,
           'subscription_value' => $this->subscription_value,
           'commercial_registration_number' => $this->commercial_registration_number,
           'img_profile' => $this->img_profile_path,
           'img_background' => $this->img_background_path,
           'category_name' => $this->category->name,


       ];
       if ($request->route()->getName() == 'markets.show') {
           $data['products'] = ProductsResource::collection($this->whenLoaded('products')->paginate(5));
       }
         return $data;
    }
}

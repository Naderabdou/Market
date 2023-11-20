<?php

namespace App\Http\Resources;

use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public $user;
public function __construct($resource)
    {
        parent::__construct($resource);
        $this->user = $resource;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array

    {




      $data = [
          'id' => $this->user->id,
            'name' => $this->user->name,
            'phone' => $this->user->phone,
            'address' => $this->user->address,
            'type' => $this->user->type,
            'status' => $this->user->status,
          //'token' => $this->token,


        ];
if ($request->user()) {


    if ($this->user->type == 'market') {



        $data['account_number'] = $this->user->account_number;
        $data['account_name'] = $this->user->account_name;
        $data['api_domain'] = $this->user->api_domain;
        $data['name_branch'] = $this->user->name_branch;
        $data['subscription_value'] = $this->user->subscription_value;
        $data['img_profile'] = $this->user->img_profile_path;
        $data['img_background'] = $this->user->img_background_path;
        $data['market'] = $this->user->market;
        $data['commercial_registration_number'] = $this->user->commercial_registration_number;
        //$data['category_'] = $this->category;
    }
    if ($this->user->type == 'driver') {

        $data['date_of_birth'] = $this->user->date_of_birth;
        $data['vehicle_number'] = $this->user->vehicle_number;
        $data['account_number'] = $this->user->account_number;
        $data['account_name'] = $this->user->account_name;
        $data['api_domain'] = $this->user->api_domain;
        $data['subscription_value'] = $this->user->subscription_value;
        $data['order_value'] = $this->user->order_value;
        $data['img_profile'] = $this->user->img_profile_path;
        $data['market'] = $this->user->market;

    }
    $data['registration_date'] = $this->user->registration_date;
    $data['expiry_date'] = $this->user->expiry_date;

    $data['created_at'] = $this->user->created_at;
    $data['updated_at'] = $this->user->updated_at;
    $data['token'] = $this->token;
}

      return $data;




    }
}

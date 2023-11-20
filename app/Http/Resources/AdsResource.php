<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

       $data = [
           'id' => $this->id,
           'title' => $this->title,
           'link' => $this->link,
           'image' => $this->image_path,
           'status' => $this->status,
           'user_id' => $this->user->name,
       ];
         return $data;
    }
}

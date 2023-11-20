<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExstraResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function __construct($resource)
    {
        parent::__construct($resource);
    }
    public function toArray(Request $request): array
    {

            $data= [
                'id' => $this->id,
                'name_extra' => $this->name_extra,
                'images' => new ImagesResource($this->images->first()),
                'price_extra' => $this->price_extra,
                'status_extra' => $this->status_extra,
                'product_id' => $this->product_id,


            ];
            if ($request->route()->getName() == 'extras.show') {
                $data['images'] = ImagesResource::collection($this->whenLoaded('images')->paginate(5));
            }



            return $data;
    }
}

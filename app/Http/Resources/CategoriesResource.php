<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class CategoriesResource extends JsonResource
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
            'name' => $this->name,
            'img_category' => $this->img_category_path,
           // 'markets' => MarketsResource::collection($this->whenLoaded('markets'))

            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d'),
            'updated_at' => Carbon::parse($this->updated_at)->format('Y-m-d'),

        ];
       if ($request->route()->getName() == 'categories.show') {
            $data['markets'] = MarketsResource::collection($this->whenLoaded('markets')->paginate(5));
        }
        return $data;

    }
}

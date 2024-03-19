<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MainCategoriesResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id'=>$this->random_id,
            'title'=>$this->title,
            'sub_categories'=>BookCategoriesResource::collection($this->whenLoaded('SubCategories'))

        ];
    }
}

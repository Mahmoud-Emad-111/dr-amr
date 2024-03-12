<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticlesFromCategoriesResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->random_id,
            'title' => $this->title,
            'image' => asset(Storage::url($this->image)),
            'content' => $this->title,
            'created_at' => $this->created_at,
            'visit_count' => $this->visit_count,

        ];
    }
}

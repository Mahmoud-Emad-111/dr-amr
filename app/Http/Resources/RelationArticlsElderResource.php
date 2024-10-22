<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class RelationArticlsElderResource extends JsonResource
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
            'visit_count' => $this->visit_count,
            'created_at' => $this->created_at->format('Y-m-d'),
            'status' => $this->status,
            'elder' => [
                'id' => $this->elder->random_id,
                'name' => $this->elder->name,
                'image' => asset(Storage::url($this->elder->image)),
            ],
            'is_Favourte' => $this->isFav == 1 ? True : False,

        ];
    }
}

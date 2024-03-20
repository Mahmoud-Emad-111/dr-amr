<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ArticlesfavirateUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->random_id,
            "title" => $this->title,
            "image" => asset(Storage::url($this->image)),
            "articles" => asset(Storage::url($this->articles)),
            "status" => $this->status,
            'elder' => [
                'name' => $this->elder->name,
                'image' => asset(Storage::url($this->elder->image)),
            ],
        ];
    }
}

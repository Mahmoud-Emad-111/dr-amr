<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AudiofavirateUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=>$this->random_id,
            "title"=>$this->title,
            "image"=>$this->image,
            "audio"=>$this->audio,
            "status"=>$this->status,
            "elder_id"=>$this->random_id,
            "audios_categories_id"=>$this->random_id,
            "visits_count"=>$this->visits_count,
        ];
    }
}

<?php

namespace App\Http\Resources;

use App\Http\Resources\ElderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AudioAllElderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return  [
            'id' => $this->random_id,
            'title' => $this->title,
            'image' => asset(Storage::url( $this->image)),
            'audio' => asset( Storage::url( $this->audio)),
            'status' => $this->status,
            'elder' => ElderResource::make($this->whenLoaded('elder')),
        ];
    }
}

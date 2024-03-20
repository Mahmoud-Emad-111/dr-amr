<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class IdBookResource extends JsonResource
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
            'name' => $this->name,
            'Book' => asset(Storage::url($this->file)),
            'image' => asset(Storage::url($this->image)),
            'status' => $this->status,
            'is_Favourte' => $this->isFav == 1 ? True : False,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Books_and_Elder_resource extends JsonResource
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
            'image' => asset(Storage::url($this->image)),
            'Book' => asset(Storage::url($this->file)),
            'status' => $this->status,
            'elder' => elderdataresource::make($this->whenLoaded('elder')),
        ];
    }
}

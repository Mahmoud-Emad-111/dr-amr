<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class FileBookResoure extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->random_id,
            'name' => $this->name,
            'image' => asset(Storage::url($this->image)),
            'status' => $this->status,
            'elder' => BookElderResource::collection($this->whenLoaded('elder')),
        ];

        // Check if the user is authenticated
        if (auth()->check()) {
            // If authenticated, include the 'Book' field
            $isFav = $this->isFav !== null;
            $data['Book'] = $isFav ? 'isFav' : 'NotFav';
        }

        return $data;
    }
}

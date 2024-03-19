<?php

namespace App\Http\Resources;

use App\Models\Image_Categories;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class imageResource extends JsonResource
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
            'image' => asset(Storage::url($this->image)),
            'image_category' => ImageCategoryResource::make(Image_Categories::find($this->image_category_id)),
            'status'=>$this->status,
        ];
    }
}


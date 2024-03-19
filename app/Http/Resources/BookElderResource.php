<?php

namespace App\Http\Resources;

use App\Models\Elder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookElderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

    //    return $this->resource;
        return [
            'id' => $this->random_id,
            'name' => $this->name,
            'image' => asset(Storage::url($this->image)),
            'email' => $this->email,
            'phone_number'=> $this->phone_number,


        ];





    }
}

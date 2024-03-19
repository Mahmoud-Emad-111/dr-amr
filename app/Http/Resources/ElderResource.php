<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ElderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=> $this->random_id,
           'name' => $this->name,
           'image' => asset(Storage::url($this->image)),
           'email' => $this->email,
            'status'=>$this->status,
           'phone' => $this->phone_number,

        ];
    }
}

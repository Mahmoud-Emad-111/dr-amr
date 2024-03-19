<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return[
            'id' => $this->random_id,
            'name' => $this->name,
            'phonenumber'=>$this->phonenumber,
            'email'=>$this->email,
            'password'=>$this->password,
        ];
    }
}

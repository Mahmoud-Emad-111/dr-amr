<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageRresource extends JsonResource
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
            'first_name'=>$this->first_name,
            'phone'=>$this->phone,
            'email'=>$this->email,
            'subject'=>$this->subject,
        ];
    }
}

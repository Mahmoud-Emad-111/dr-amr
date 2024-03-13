<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
<<<<<<< HEAD
=======
<<<<<<< Updated upstream
            "id"=>$this->random_id,
            "title"=>$this->title,
            "image"=>$this->image,
            "audio"=>$this->audio,
            "status"=>$this->status,
            "elder_id"=>$this->random_id,
            "audios_categories_id"=>$this->random_id,
            "visits_count"=>$this->visits_count,
=======
>>>>>>> 57a5806bd1f5b6a71ebb586fe6d65062152cdd06
            "id" => $this->random_id,
            "title" => $this->title,
            "image" => asset(Storage::url($this->image)),
            "audio" => asset(Storage::url($this->audio)),
            "status" => $this->status,
            'elder' => [
                'name' => $this->elder->name,
                'image' => asset(Storage::url($this->elder->image)),
<<<<<<< HEAD
                // 'audio' => asset(Storage::url($this->audio)),
            ],
=======
            ],
>>>>>>> Stashed changes
>>>>>>> 57a5806bd1f5b6a71ebb586fe6d65062152cdd06
        ];
    }
}

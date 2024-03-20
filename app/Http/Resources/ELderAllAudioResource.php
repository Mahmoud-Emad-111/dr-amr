<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ELderAllAudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        // $audios = $this->whenLoaded('audio', function () {
        //     return $this->audio->map(function ($audio) {
        //         $audio->is_Favourite = $audio->is_Favourite ?? false;
        //         return new AudioResource($audio);
        //     });
        // });

        // return [
        //     'id' => $this->random_id,
        //     'name' => $this->name,
        //     'image' => asset(Storage::url($this->image)),
        //     'email' => $this->email,
        //     'phone_number' => $this->phone_number,
        //     'audios' => $audios,
        //     'count_audios'=>count($this->audio),
        //     'is_Favourite' => $this->is_Favourite ?? false, // Add this line
        // ];

        return [
            'id' => $this->random_id,
            'name'=> $this->name,
            'image'=> asset(Storage::url($this->image)),
            'email'=> $this->email,
            'phone_number'=>$this->phone_number,
            'Audio' => AudioResource::collection($this->whenLoaded('Audio')),
            'count_audios'=>count($this->audio),

        ];
    }
}

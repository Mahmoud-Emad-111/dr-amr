<?php

namespace App\Http\Resources;

use App\Models\Elder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class AudioResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = Elder::with('audio')->find($this->id);
        // return [
        //     'id' => $data->random_id,
        //     'name'=> $data->name,
        //     'image'=> asset(Storage::url($data->image)),
        //     // 'email'=> $data->email,
        //     // 'phone_number'=>$data->phone_number,
        //     // 'Audio' => AudioResource::collection($data->whenLoaded('Audio')),
        //     'count_audios'=>count($data->audio),
        // ];
        return [
            'id' => $this->random_id,
            'title' => $this->title,
            'image' => asset(Storage::url($this->image)),
            'audio' => asset(Storage::url($this->audio)),
            'status' => $this->status,
            'visits_count' => $this->visits_count,
            'elder' => [
                'name' => $this->elder->name,
                'image' => asset(Storage::url($this->elder->image)),
                'audio' => asset(Storage::url($this->audio)),
                'is_Favourte' => $this->isFav == 1 ? True : False,

            ],
        ];
    }
}

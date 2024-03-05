<?php

namespace App\Http\Resources;

use App\Models\Elder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class All_Audios_CategoriesResources extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         $data=Elder::find($this->elder_id);
        return [
            'id' => $data->audio->id,
            'name'=> $data->name,
            'image'=> asset(Storage::url($data->image)),
            'email'=> $data->email,
            'phone_number'=>$data->phone_number,
            // 'Audio' => AudioResource::collection($data->whenLoaded('Audio')),
            'count_audios'=>count($data->audio),
        ];
    }
}

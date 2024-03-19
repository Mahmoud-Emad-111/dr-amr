<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'facebook'=>$this->facebook,
            'whatsapp'=>$this->whatsapp,
            'messenger'=>$this->messenger,
            'instagram'=>$this->instagram,
            'image'=>asset(Storage::url($this->image)),
            'prayer_timings'=>$this->prayer_timings==1 ? 'true':'false',
            'facebook'=>$this->facebook,
        ];
    }
}

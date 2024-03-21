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
            'facebook'=>$this->facebook==null?'' : $this->facebook,
            'whatsapp'=>$this->whatsapp==null?'' : $this->whatsapp,
            'messenger'=>$this->messenger==null?'' : $this->messenger,
            'instagram'=>$this->instagram==null?'' : $this->instagram,
            'play_store'=>$this->play_store==null?'' : $this->play_store,
            'app_store'=>$this->app_store==null?'' : $this->app_store,
            'image'=>$this->image==null?'':asset(Storage::url($this->image)),
            'logo'=>$this->logo==null?'':asset(Storage::url($this->logo)),
            'prayer_timings'=>$this->prayer_timings==1 ? 'true':'false',
            // 'facebook'=>$this->facebook,
        ];
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;


class ArticlsResource extends JsonResource
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
            'title' => $this->title,
            'image' => asset(Storage::url($this->image)),
<<<<<<< HEAD
            'visit_count' => $this->visit_count,
            'created_at' => $this->created_at->format('Y-m-d'),

            // 'content' => $this->title,
=======
// <<<<<<< Updated upstream
            'content' => $this->title,
// =======
            // 'visit_count'=>$this->visit_count,
            'created_at'=>$this->created_at,

            // 'content' => $this->title,
// >>>>>>> Stashed changes
>>>>>>> 57a5806bd1f5b6a71ebb586fe6d65062152cdd06

            // 'elder_id'=> $this->elder_id
        ];
    }
}

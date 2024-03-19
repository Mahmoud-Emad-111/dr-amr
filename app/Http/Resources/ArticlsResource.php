<?php

namespace App\Http\Resources;

use App\Models\Articles_Categories;
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
        $category=Articles_Categories::find($this->articles_categories_id);
        return [
            'id' => $this->random_id,
            'title' => $this->title,
            'image' => asset(Storage::url($this->image)),
            'content' => $this->content,
            'status' => $this->status,
            'visit_count' => $this->visit_count,
            'created_at' => $this->created_at->format('Y-m-d'),
            'elder' => [
                'id' => $this->elder->random_id,
                'name' => $this->elder->name,
                'image' => asset(Storage::url($this->elder->image)),
            ],
            "Category"=>ArticlesCategoriesResources::make($category),
        ];
    }
}

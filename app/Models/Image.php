<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'image_categories_id',
        'random_id',
        'status',

    ];
    public function image_category()
    {
        return $this->belongsTo(Image_Categories::class);
    }
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}

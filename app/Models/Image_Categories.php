<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image_Categories extends Model
{
    use HasFactory;
    protected $table='image_categories';
    protected $fillable=[
        'title',
        'random_id',

    ];

    // Book
    public function Image(){
        return $this->hasMany(Image::class,'image_categories_id','id');
    }
}

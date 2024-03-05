<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudiosCategories extends Model
{
    use HasFactory;
    protected $fillable=[
        'random_id',

        'title',
    ];
    public function audios(){
        return $this->hasMany(Audio::class);
    }
}

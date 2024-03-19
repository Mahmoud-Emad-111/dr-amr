<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Elder extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'image',
        'email',
        'phone_number',
        'random_id',
        'status',
        'tag'
];
// protected $casts = [
//     'tag_name' => 'array',
// ];
// Book
    public function books(){
        return $this->hasMany(Book::class);
    }
// Audio
public function Audio(){
    return $this->hasMany(Audio::class);
}
// Article
public function Article(){
    return $this->hasMany(Articles::class);
}
public function user()
{
    return $this->belongsToMany(User::class);
}
}

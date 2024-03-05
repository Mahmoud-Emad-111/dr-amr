<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;

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
];
// Book
    public function books(){
        return $this->hasMany(Book::class);
    }
// Audio
public function Audio(){
    return $this->hasMany(Audio::class);
}
// Audio
public function Article(){
    return $this->hasMany(Artisan::class);
}
public function user()
{
    return $this->belongsToMany(User::class);
}
}

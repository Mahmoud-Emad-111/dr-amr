<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',

        'phonenumber',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function Audio()
    {
        return $this->belongsToMany(Audio::class, 'download_audio');
    }
    public function image()
    {
        return $this->belongsToMany(Image::class, 'download_images');
    }
    public function book()
    {
        return $this->belongsToMany(Book::class, 'download_books');
    }
    public function elder()
    {
        return $this->belongsToMany(Elder::class, 'downloadelders');
    }




    public function Audios()
    {
        return $this->belongsToMany(Audio::class,'Favirateaudios');
    }
    public function images()
    {
        return $this->belongsToMany(Image::class, 'favirateimages');
    }
    public function books()
    {
        return $this->belongsToMany(Book::class, 'faviratebooks');
    }
    public function elders()
    {
        return $this->belongsToMany(Elder::class,'_favirateelders');
    }
}
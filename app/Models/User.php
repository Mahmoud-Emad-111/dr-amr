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




    public function Favirate_Audios()
    {
        return $this->belongsToMany(Audio::class, 'favirateaudios');
    }
    public function Favirate_images()
    {
        return $this->belongsToMany(Image::class, 'favirateimages');
    }

    public function Favirate_Books()
    {
        return $this->belongsToMany(Book::class, 'faviratebooks');
    }



    public function Favirate_Elder()
    {
        return $this->belongsToMany(Elder::class, 'favirateelders');
    }



    public function Favirate_Articles()
    {
        return $this->belongsToMany(Articles::class, 'favirateArticles');
    }
}

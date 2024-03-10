<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audio extends Model
{
    use HasFactory;
    protected $table = 'audios';

    protected $fillable = [
        'title',
        'image',
        'audio',
        'status',
        'elder_id',
        'audios_categories_id',
        'random_id',
        'visits_count',
        'tag_name'


    ];
    // protected $casts = [
    //     'tag_name' => 'array',
    // ];
    // this  hidden data
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function elder()
    {
        return $this->belongsTo(Elder::class);
    }
    public function category()
    {
        return $this->belongsTo(AudiosCategories::class);
    }
    public function User()
    {
        return $this->belongsToMany(User::class);
    }
}

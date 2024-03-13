<?php

namespace App\Models;

use App\Traits\RandomIDTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory, RandomIDTrait;

    protected $fillable = [
        'title',
        'image',
         'content',
        'elder_id',
        'random_id',
        'articles_categories_id',
        'status',
        'tag_name',
        'visit_count',
    ];
    // protected $casts = [
    //     'tag_name' => 'array',
    // ];
    // this  hidden data
    protected $hidden =[
        'created_at',
        'updated_at',
    ];
    public function elder(){
        return $this->belongsTo(Elder::class);
    }
    public function Category(){
        return $this->belongsTo(Articles_Categories::class);
    }

}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles_Categories extends Model
{
    use HasFactory;
    protected $table='articles_categories';
    protected $fillable=[
        'title',
        'random_id',
    ];
    public function Articles(){
        return $this->hasMany(Articles::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Main_Categories_Book extends Model
{
    use HasFactory;
    protected $fillable=[
        'random_id',
        'title',
    ];

    public function SubCategories(){
        return $this->hasMany(BooksCategories::class,'main__categories__books_id','id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BooksCategories extends Model
{
    use HasFactory;
    protected $fillable=[
        'random_id',
        'main__categories__books_id',
        'title',
    ];

    public function Books(){
        return $this->hasMany(Book::class);
    }
    public function Main_Category(){
        return $this->belongsTo(Main_Categories_Book::class,);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'random_id',

        'name',
        'file',
        'image',
        'books_categories_id',
        'status'

    ];
    public function Category(){
        return $this->belongsTo(BooksCategories::class);
    }
    public function user()
    {
        return $this->belongsToMany(User::class);
    }
}

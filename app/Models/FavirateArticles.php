<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faviratearticles extends Model // تغيير "FavirateArticles" إلى "Faviratearticles"
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'articles_id',
    ];
}

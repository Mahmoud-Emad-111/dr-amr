<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadBook extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'book_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PdfDocument extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'file',
        'elder_id',
        'random_id',

    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Downloadelder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'elder_id',
    ];
}

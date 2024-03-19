<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Terms_Conditions extends Model
{
    use HasFactory;
    protected $table = 'terms__conditions';
    protected $fillable = [
        'text',
        'country',
        'text_en',
        'country_en',
    ];
}

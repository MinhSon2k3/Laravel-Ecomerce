<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Language extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'canonical',
        'publish',
        'user_id',
        'image',
        'description',
      
    ];

    protected $table='languages';

   
}

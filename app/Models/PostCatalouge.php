<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCatalouge extends Model
{
    use HasFactory;
    protected $fillable = [
        'parentid',
        'lft',
        'rgt',
        'level',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow',
  
      
    ];

    protected $table='post_catalouges';
}

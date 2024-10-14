<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCatalouge extends Model
{  
    use HasFactory;

    protected $table='post_catalouges';

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

    public function languages(){
        return $this->belongsToMany(Language::class, 'post_catalouge_language' , 'post_catalouge_id', 'language_id')
        ->withPivot(
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        )->withTimestamps();
    }
}

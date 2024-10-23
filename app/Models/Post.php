<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Post extends Model
{  
    use HasFactory;

    protected $table='posts';

    protected $fillable = [
       'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'post_catalouge_id',
  
      
    ];

    public function languages(){
        return $this->belongsToMany(Language::class, 'post_languages' , 'post_id', 'language_id')
        ->withPivot(
            'name',
            'canonical',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'description',
            'content'
        )->withTimestamps();
    }

    public function post_catalouges(){
        return $this->belongsToMany(PostCatalouge::class,'post_catalouge_posts','post_id','post_catalouge_id');
    }

}

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
        'post_catalouge_id',
        'image',
        'icon',
        'album',
        'publish',
        'order',
        'user_id',
        'follow',
  
      
    ];

    public function languages(){
        return $this->belongsToMany(Language::class, 'post_languages' , 'language_id', 'id')
        ->withPivot(
            'post_catalouge_id' ,
            'language_id',
            'name',
            'description',
            'content',
            'meta_title',
            'meta_keyword',
            'meta_description',
            'canonical'
        )->withTimestamps();
    }

    public function post_catalouges(){
        return $this->hasMany(PostCatalouge::class,'post_catalouge_posts','post_catalouge_id','id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class PostCatalouge extends Model
{  
    use HasFactory;

    protected $table='post_catalouges';

    protected $fillable = [
        'parent_id',
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
        return $this->belongsToMany(Language::class, 'post_catalouge_languages' , 'post_catalouge_id', 'language_id')
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

    public function post_catalouge_languages(){
        return $this->hasMany(PostCatalougeLanguage::class,'post_catalouge_id','id');
    }


    public static function isNodeCheck($id=0){
      $postCatalouge=PostCatalouge::find($id);
      dd($postCatalouge);
    }
}

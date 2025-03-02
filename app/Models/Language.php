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
        'current',
      
    ];

    protected $table='languages';


    public function post_catalouges(){
        return $this->belongsToMany(PostCatalouge::class, 'post_catalouge_languages' , 'language_id', 'post_catalouge_id')
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

    public function menus(){
        return $this->belongsToMany(PostCatalouge::class, 'post_catalouge_languages' , 'language_id', 'menu_id')
        ->withPivot(
            'name',
            'canonical'
        )->withTimestamps();
    }

   
}
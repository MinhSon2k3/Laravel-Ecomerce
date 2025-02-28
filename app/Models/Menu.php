<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory,SoftDeletes;

    protected $table='menus';

    protected $fillable = [
        'menu_catalouge_id',
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
        'type',
      
    ];
    public function languages(){
        return $this->belongsToMany(Language::class, 'menu_languages' , 'menu_id', 'language_id')
        ->withPivot(
            'menu_id' ,
            'language_id',
            'name',
            'canonical'
        )->withTimestamps();
    }
}
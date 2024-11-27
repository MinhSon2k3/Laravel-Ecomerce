<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AttributeCatalouge extends Model
{  
    use HasFactory;

    protected $table='attribute_catalouges';

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

    public function attributes(){
      return $this->belongsToMany(Attribute::class, 'attribute_catalouge_attribute' , 'attribute_catalouge_id', 'attribute_id');
    }

    public function languages(){
        return $this->belongsToMany(Language::class, 'attribute_catalouge_languages' , 'attribute_catalouge_id', 'language_id')
        ->withPivot(
            'attribute_catalouge_id' ,
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

    public function attribute_catalouge_languages(){
        return $this->hasMany(AttributeCatalougeLanguage::class,'attribute_catalouge_id','id');
    }


    public static function isNodeCheck($id=0){
      $attributeCatalouge=AttributeCatalouge::find($id);
      if($attributeCatalouge->rgt - $attributeCatalouge->lft !=1){
        return false;
      }
      else{
        return true;
      }
    }
}

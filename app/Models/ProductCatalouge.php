<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ProductCatalouge extends Model
{  
    use HasFactory;

    protected $table='product_catalouges';

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

    public function products(){
      return $this->belongsToMany(Product::class, 'product_catalouge_product' , 'product_catalouge_id', 'product_id');
    }

    public function languages(){
        return $this->belongsToMany(Language::class, 'product_catalouge_languages' , 'product_catalouge_id', 'language_id')
        ->withPivot(
            'product_catalouge_id' ,
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

    public function product_catalouge_languages(){
        return $this->hasMany(ProductCatalougeLanguage::class,'product_catalouge_id','id');
    }


    public static function isNodeCheck($id=0){
      $productCatalouge=ProductCatalouge::find($id);
      if($productCatalouge->rgt - $productCatalouge->lft !=1){
        return false;
      }
      else{
        return true;
      }
    }
}

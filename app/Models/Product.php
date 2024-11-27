<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Product extends Model
{  
    use HasFactory;

    protected $table='products';

    protected $fillable = [
       'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'product_catalouge_id',
        'code',
        'price',
        'made_in',
    ];

    public function languages(){
        return $this->belongsToMany(Language::class, 'product_languages' , 'product_id', 'language_id')
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
    public function product_catalouges(){
        return $this->belongsToMany(ProductCatalouge::class,'product_catalouge_products','product_id','product_catalouge_id');
    }
}

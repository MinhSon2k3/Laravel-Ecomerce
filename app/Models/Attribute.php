<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Attribute extends Model
{  
    use HasFactory;

    protected $table='attributes';

    protected $fillable = [
       'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        'attribute_catalouge_id',
    ];

    public function languages(){
        return $this->belongsToMany(Language::class, 'attribute_languages' , 'attribute_id', 'language_id')
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
    public function attribute_catalouges(){
        return $this->belongsToMany(AttributeCatalouge::class,'attribute_catalouge_attribute','attribute_id','attribute_catalouge_id');
    }
    public function attribute_language(){
        return $this->hasMany(AttributeLanguage::class, 'attribute_id');
    }

    public function product_variants(){
        return $this->belongsToMany(ProductVariant::class,'product_variant_attributes','attribute_id','product_variant_id')
        ->withPivot('name')
        ->withTimestamps();
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCatalougeLanguage extends Model
{
    use HasFactory;
    protected $table = 'product_catalouge_languages';

    public function product_catalouges(){
        return $this->belongsTo(ProductCatalouge::class, 'product_catalouge_id', 'id');
    }
}

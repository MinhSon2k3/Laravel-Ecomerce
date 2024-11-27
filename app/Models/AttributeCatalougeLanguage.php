<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeCatalougeLanguage extends Model
{
    use HasFactory;
    protected $table = 'attribute_catalouge_languages';

    public function attribute_catalouges(){
        return $this->belongsTo(AttributeCatalouge::class, 'attribute_catalouge_id', 'id');
    }
}

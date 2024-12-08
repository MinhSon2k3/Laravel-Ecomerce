<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeLanguage extends Model
{
    use HasFactory;

    protected $table = 'attribute_languages';

    public function attributes(){
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

}

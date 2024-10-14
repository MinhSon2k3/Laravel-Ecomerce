<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostCatalougeLanguage extends Model
{
    use HasFactory;
    protected $table = 'post_catalouge_language';

    public function post_catalouges(){
        return $this->belongsTo(PostCatalogue::class, 'post_catalouge_id', 'id');
    }
}

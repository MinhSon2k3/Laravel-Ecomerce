<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class {ModuleTemplate} extends Model
{  
    use HasFactory;

    protected $table='{tableName}';

    protected $fillable = [
       'image',
        'album',
        'publish',
        'follow',
        'order',
        'user_id',
        '{relation}_id',
    ];

    public function languages(){
        return $this->belongsToMany(Language::class, '{pivotTable}' , '{foreignkey}', 'language_id')
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
    public function {relation}s(){
        return $this->belongsToMany({relationTable}::class,'{relationPivot}s','{foreignkey}','{relation}_id');
    }
}

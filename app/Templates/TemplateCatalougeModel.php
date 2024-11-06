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

    public function {relation}s(){
      return $this->belongsToMany({relationTable}::class, '{relationPivot}' , '{foreignkey}', '{relation}_id');
    }

    public function languages(){
        return $this->belongsToMany(Language::class, '{pivotTable}' , '{foreignkey}', 'language_id')
        ->withPivot(
            '{foreignkey}' ,
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

    public function {pivotTable}_languages(){
        return $this->hasMany({pivotModel}::class,'{foreignkey}','id');
    }


    public static function isNodeCheck($id=0){
      ${relation}Catalouge={ModuleTemplate}::find($id);
      if(${relation}Catalouge->rgt - ${relation}Catalouge->lft !=1){
        return false;
      }
      else{
        return true;
      }
    }
}

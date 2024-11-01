<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCatalouge extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'publish',
      
    ];

    protected $table='user_catalouges';

    public function users(){
        return $this->hasMany(User::class,'user_catalouge_id','id');
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'user_catalouge_permissions' , 'user_catalouge_id', 'permission_id')
        ->withPivot(
            'name',
            'canonical'
        )->withTimestamps();
    }

}

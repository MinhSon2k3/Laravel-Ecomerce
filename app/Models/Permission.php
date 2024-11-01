<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'canonical',
    ];

    protected $table='permissions';
    
    
    public function user_catalouges(){
        return $this->belongsToMany(UserCatalouge::class, 'user_catalouge_permissions' , 'permission_id', 'user_catalouge_id')
        ->withPivot(
            'name',
            'canonical'
        )->withTimestamps();
    }
}

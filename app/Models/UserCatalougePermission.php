<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCatalougePermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_catalouge_id',
        'permission_id',
    ];

    protected $table='user_catalouge_permission';

    public function user_catalouges(){
        return $this->belongsTo(UserCatalouge::class,'user_catalouge_id');
    }

    public function permissions(){
        return $this->belongsTo(Permission::class,'permission_id');
    }

}

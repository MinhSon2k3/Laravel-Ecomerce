<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class MenuCatalouge extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='menu_catalouges';

    protected $fillable = [
        'name',
        'keyword',
        'publish'
      
    ];
}